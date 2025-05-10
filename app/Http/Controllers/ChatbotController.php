<?php

namespace App\Http\Controllers;

use App\Models\Phim;
use App\Models\TheLoai;
use App\Models\ChiTietPhim;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\KhachHang;
use App\Models\ChatMessage;
use App\Models\TaiChinh;

class ChatbotController extends Controller
{
    private $apiKey;
    private $geminiUrl = 'https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent';
    private static $conversationContext = [];

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY', '');
    }

    public function query(Request $request)
    {
        try {
            // Validate request
            $validatedData = $request->validate([
                'message' => 'required|string|max:1000',
                'userId' => 'required|string',
            ]);

            $message = strtolower($validatedData['message']);
            $userId = $validatedData['userId'];

            // Handle balance queries
            if (str_contains($message, 'số dư') || str_contains($message, 'so du') ||
                str_contains($message, 'kiểm tra tài khoản') || str_contains($message, 'xem tiền')) {

                // If user is not logged in (guest)
                if (strpos($userId, 'guest_') === 0) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Vui lòng đăng nhập để kiểm tra số dư tài khoản.'
                    ]);
                }

                $customer = KhachHang::find($userId);
                if (!$customer) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Không tìm thấy thông tin khách hàng. Vui lòng thử lại.'
                    ]);
                }

                $response = "Số dư của bạn là: " . number_format($customer->so_du, 0, ',', '.') . " VNĐ";

                // Store the chat message
                    ChatMessage::create([
                        'khach_hang_id' => $userId,
                        'message' => $message,
                        'response' => $response
                    ]);

                return response()->json([
                    'status' => true,
                    'message' => $response
                ]);
            }

            // Store non-balance related messages for logged-in users
            if (strpos($userId, 'guest_') !== 0) {
                // Get response first
                $contextData = $this->getContextData($message);
                $response = $this->callGeminiApi($message, $contextData);

                // Then store the message with the response
                ChatMessage::create([
                    'khach_hang_id' => $userId,
                    'message' => $message,
                    'response' => $response
                ]);

                return response()->json([
                    'status' => true,
                    'message' => $response
                ]);
            }

            // For guest users, just get and return the response
            $contextData = $this->getContextData($message);
            $response = $this->callGeminiApi($message, $contextData);

            return response()->json([
                'status' => true,
                'message' => $response
            ]);

            // Xử lý câu hỏi về thuộc tính cụ thể của phim
            $attributeKeywords = [
                // Trailer
                'trailer' => [
                    'trailer',
                    'xem trước',
                    'preview',
                    'teaser',
                    'video giới thiệu',
                    'xem phim trước',
                    'đoạn phim ngắn',
                    'tập phim đầu tiên'
                ],

                // Thời lượng
                'thoi_luong' => [
                    'thời lượng',
                    'dài bao lâu',
                    'bao nhiêu phút',
                    'mấy tiếng',
                    'phim dài bao lâu',
                    'thời gian phim',
                    'phim có mấy phút'
                ],

                // Đạo diễn
                'dao_dien' => [
                    'đạo diễn',
                    'ai đạo diễn',
                    'người đạo diễn',
                    'được chỉ đạo bởi ai',
                    'phim của ai đạo diễn',
                    'ai là đạo diễn'
                ],

                // Diễn viên
                'dien_vien' => [
                    'diễn viên',
                    'ai đóng',
                    'cast',
                    'người đóng',
                    'nghệ sĩ',
                    'diễn viên chính',
                    'ai là người đóng vai',
                    'dàn diễn viên'
                ],

                // Quốc gia
                'quoc_gia' => [
                    'quốc gia',
                    'nước nào',
                    'sản xuất ở đâu',
                    'phim nước nào',
                    'quốc gia sản xuất',
                    'nước làm phim',
                    'phim từ đâu'
                ],

                // Ngày khởi chiếu
                'ngay_khoi_chieu' => [
                    'ngày khởi chiếu',
                    'bắt đầu chiếu',
                    'chiếu khi nào',
                    'khi nào chiếu',
                    'công chiếu',
                    'ngày ra mắt',
                    'phim ra mắt ngày nào'
                ],

                // Ngày kết thúc
                'ngay_ket_thuc' => [
                    'ngày kết thúc',
                    'hết chiếu',
                    'chiếu đến khi nào',
                    'đến bao giờ',
                    'phim chiếu đến khi nào',
                    'chiếu xong khi nào'
                ],

                // Mô tả
                'mo_ta' => [
                    'mô tả',
                    'nội dung',
                    'phim nói về',
                    'tóm tắt',
                    'review',
                    'giới thiệu',
                    'về cái gì',
                    'phim kể về',
                    'phim là gì',
                    'nội dung phim'
                ],

                // Giá bán
                'gia_ban' => [
                    'giá',
                    'bao nhiêu tiền',
                    'giá vé',
                    'mất bao nhiêu',
                    'phí',
                    'chi phí',
                    'giá thuê',
                    'giá mua',
                    'bao nhiêu tiền một vé',
                    'giá vé là bao nhiêu'
                ]
            ];


            foreach ($attributeKeywords as $attribute => $keywords) {
                foreach ($keywords as $keyword) {
                    if (str_contains($message, $keyword)) {
                        return $this->getMovieAttribute($message, $attribute);
                    }
                }
            }

            // Xử lý câu hỏi về phim hot/phim được xem nhiều nhất
            if (
                str_contains($message, 'phim hot') ||
                str_contains($message, 'phim hay nhất') ||
                str_contains($message, 'phim được xem nhiều') ||
                str_contains($message, 'top phim') ||
                str_contains($message, 'hot phim') ||
                str_contains($message, 'phim nhiều lượt xem') ||
                str_contains($message, 'phim nổi bật') ||
                str_contains($message, 'phim xu hướng') ||
                str_contains($message, 'phim trending') ||
                str_contains($message, 'phim nổi tiếng') ||
                str_contains($message, 'phim xem nhiều nhất') ||
                str_contains($message, 'phim ăn khách') ||
                str_contains($message, 'phim hot nhất') ||
                str_contains($message, 'top phim hay') ||
                str_contains($message, 'top phim hot') ||
                str_contains($message, 'phim hot trend') ||
                str_contains($message, 'phim đang hot') ||
                str_contains($message, 'phim viral') ||
                str_contains($message, 'phim đang nổi') ||
                str_contains($message, 'hot') ||
                str_contains($message, 'phim hay') ||
                str_contains($message, 'phim hay nhất') ||
                str_contains($message, 'phim được xem nhiều') ||
                str_contains($message, 'phim được xem nhiều nhất')
            ) {
                return $this->getHotMovies();
            }

            // Xử lý câu hỏi về phim mới
            if (
                str_contains($message, 'phim mới') ||
                str_contains($message, 'phim mới nhất') ||
                str_contains($message, 'phim mới ra') ||
                str_contains($message, 'phim mới ra mắt') ||
                str_contains($message, 'phim vừa ra') ||
                str_contains($message, 'phim vừa chiếu') ||
                str_contains($message, 'phim sắp chiếu') ||
                str_contains($message, 'phim mới chiếu') ||
                str_contains($message, 'phim chuẩn bị chiếu') ||
                str_contains($message, 'phim ra rạp') ||
                str_contains($message, 'mới ra rạp') ||
                str_contains($message, 'vừa ra mắt')
            ) {
                return $this->getNewMovies(); // Hoặc có thể là getNewMovies() nếu bạn muốn phân biệt rõ giữa "hot" và "new"
            }



            // Xử lý câu hỏi về danh sách phim của một thể loại cụ thể
            if (
                preg_match('/thể loại\s+([^\s?.,]+).*?(có|gồm|bao gồm|những phim nào|phim gì|danh sách|có những|có gì|phim nào)/ui', $message, $matches) ||
                preg_match('/(?:phim|danh sách)\s+(?:thể loại|the loai)\s+([^\s?.,]+)/ui', $message, $matches)
            ) {
                return $this->getMoviesInGenre($matches[1]);
            }

            // Xử lý câu hỏi về số lượng thể loại
            if (
                str_contains($message, 'bao nhiêu thể loại') ||
                str_contains($message, 'có những thể loại nào') ||
                str_contains($message, 'danh sách thể loại') ||
                str_contains($message, 'các thể loại phim') ||
                str_contains($message, 'có mấy thể loại') ||
                str_contains($message, 'liệt kê thể loại') ||
                str_contains($message, 'thể loại có gì') ||
                str_contains($message, 'phim có bao nhiêu thể loại') ||
                str_contains($message, 'có thể loại gì') ||
                str_contains($message, 'các dòng phim') ||
                str_contains($message, 'phân loại phim') ||
                str_contains($message, 'kiểu phim nào')
            ) {
                return $this->getGenreCount();
            }


            // Xử lý câu hỏi về thể loại của một phim cụ thể
            if (
                str_contains($message, 'thuộc thể loại') ||
                str_contains($message, 'thể loại của phim') ||
                str_contains($message, 'phim gì thuộc thể loại') ||
                str_contains($message, 'thể loại gì') ||
                str_contains($message, 'phim này thể loại') ||
                str_contains($message, 'phim đó thể loại') ||
                (str_contains($message, 'thể loại') && !str_contains($message, 'phim thể loại') && !str_contains($message, 'danh sách thể loại') && !str_contains($message, 'bao nhiêu thể loại') && !str_contains($message, 'có những thể loại nào'))
            ) {
                return $this->getMovieGenre($message);
            }


            // Mảng các từ khóa cho từng loại xử lý
            $hotMovieKeywords = [
                'phim hot',
                'phim hay nhất',
                'phim được xem nhiều',
                'top phim',
                'hot phim',
                'phim nhiều lượt xem',
                'phim nổi bật',
                'phim xu hướng',
                'phim trending',
                'phim nổi tiếng',
                'phim xem nhiều nhất',
                'phim ăn khách',
                'phim hot nhất',
                'top phim hay',
                'top phim hot',
                'phim hot trend',
                'phim đang hot',
                'phim viral',
                'phim đang nổi'
            ];

            $genreCountKeywords = [
                'bao nhiêu thể loại',
                'có những thể loại nào',
                'danh sách thể loại',
                'các thể loại phim',
                'có mấy thể loại',
                'liệt kê thể loại',
                'thể loại có gì',
                'phim có bao nhiêu thể loại',
                'có thể loại gì',
                'các dòng phim',
                'phân loại phim',
                'kiểu phim nào'
            ];

            $movieGenreKeywords = [
                'thuộc thể loại',
                'thể loại của phim',
                'phim gì thuộc thể loại',
                'thể loại gì',
                'phim này thể loại',
                'phim đó thể loại'
            ];

            $currentlyShowingKeywords = [
                'phim đang chiếu',
                'đang chiếu',
                'đang phát',
                'phát hành gần đây',
                'phim hiện tại',
                'phim hôm nay',
                'đang được công chiếu',
                'đang ra rạp',
                'phim đang hot ngoài rạp',
                'phim có trong rạp',
                'rạp đang chiếu',
                'đang trình chiếu',
                'phim mới chiếu'
            ];


            $movieByGenreKeywords = [
                'phim thể loại',
                'phim thuộc thể loại',
                'thể loại phim',
                'cho tôi phim hành động',
                'phim nào thể loại',
                'có phim thể loại gì',
                'phim kinh dị nào hay',
                'phim tình cảm',
                'phim hài',
                'phim viễn tưởng',
                'tôi muốn xem phim thể loại',
                'tìm phim thể loại'
            ];


            $movieDetailsKeywords = [
                'thông tin',
                'chi tiết phim',
                'nội dung phim',
                'tóm tắt phim',
                'mô tả phim',
                'phim nói về gì',
                'phim kể về',
                'review phim',
                'giới thiệu phim',
                'thông tin chi tiết',
                'phim có những ai',
                'diễn viên phim',
                'phim',
                'phim này',
                'phim đó',

            ];


            $ticketPriceKeywords = [
                'giá',
                'giá vé',
                'vé bao nhiêu',
                'phim giá bao nhiêu',
                'mua phim hết bao nhiêu',
                'thuê phim giá',
                'mua vé',
                'vé phim bao nhiêu',
                'vé xem phim',
                'phim bao nhiêu tiền',
                'phim này giá sao',
                'vé rạp giá',
                'giá xem'
            ];


            $showtimeKeywords = [
                'lịch chiếu',
                'suất chiếu',
                'chiếu lúc mấy giờ',
                'giờ chiếu',
                'suất phim',
                'mấy giờ chiếu',
                'rạp chiếu lúc',
                'lịch chiếu phim',
                'phim có suất mấy giờ',
                'phim chiếu mấy giờ',
                'phim chiếu lúc mấy giờ',
            ];


            $priceAndViewKeywords = ['giá và lượt xem', 'bao nhiêu người xem'];
            $priceAndViewKeywords = [
                'giá và lượt xem',
                'bao nhiêu người xem',
                'phim được xem bao nhiêu',
                'phim có bao nhiêu lượt xem',
                'phim có giá bao nhiêu và được xem',
                'phim hot và giá',
                'phim nổi bật giá bao nhiêu',
                'bao nhiêu người đã xem',
                'có nhiều người xem không',
                'giá tiền và số lượt xem'
            ];

            // Hàm kiểm tra keyword có trong message
            function containsAny($message, $keywords)
            {
                foreach ($keywords as $keyword) {
                    if (str_contains($message, $keyword)) {
                        return true;
                    }
                }
                return false;
            }

            // Xử lý các loại câu hỏi
            if (containsAny($message, $hotMovieKeywords)) {
                return $this->getHotMovies();
            }

            if (containsAny($message, $genreCountKeywords)) {
                return $this->getGenreCount();
            }

            if (
                containsAny($message, $movieGenreKeywords) ||
                (str_contains($message, 'thể loại') &&
                    !containsAny($message, array_merge($genreCountKeywords, $movieByGenreKeywords)))
            ) {
                return $this->getMovieGenre($message);
            }

            if (containsAny($message, $currentlyShowingKeywords)) {
                return $this->getCurrentlyShowingMovies();
            }

            if (containsAny($message, $movieByGenreKeywords)) {
                return $this->getMoviesByGenre($message);
            }

            if (containsAny($message, $movieDetailsKeywords)) {
                return $this->getMovieDetails($message);
            }

            if (containsAny($message, $ticketPriceKeywords)) {
                return $this->getTicketPriceInfo($message);
            }

            if (containsAny($message, $showtimeKeywords)) {
                return $this->getShowtimes($message);
            }

            if (
                (str_contains($message, 'giá') && str_contains($message, 'lượt xem')) ||
                containsAny($message, $priceAndViewKeywords)
            ) {
                return $this->getMoviePriceAndViews($message);
            }

            // Nếu không khớp với các pattern có sẵn, sử dụng Gemini API
            $contextData = $this->getContextData($message);
            $response = $this->callGeminiApi($message, $contextData);

            return response()->json([
                'status' => true,
                'message' => $response,
            ]);
        } catch (Exception $e) {
            Log::error('Chatbot error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Xin lỗi, đã có lỗi xảy ra. Vui lòng thử lại sau.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getContextData($question)
    {
        $contextData = [
            'phim' => [],
            'the_loai' => []
        ];

        $normalizedQuestion = strtolower($question);

        $movieKeywords = ['phim', 'movie', 'bộ phim', 'tên phim', 'phim nào'];
        $hasMovieKeyword = false;

        foreach ($movieKeywords as $keyword) {
            if (str_contains($normalizedQuestion, $keyword)) {
                $hasMovieKeyword = true;
                break;
            }
        }

        if ($hasMovieKeyword) {
            $contextData['phim'] = Phim::where('trang_thai', 0)
                                    ->get()
                                    ->toArray();

            $contextData['the_loai'] = TheLoai::where('tinh_trang', 0)
                                            ->get()
                                            ->toArray();
        }

        return array_filter($contextData, fn($value) => !empty($value));
    }


    private function callGeminiApi($question, $contextData)
    {
        // If no context data is found
        if (empty($contextData)) {
            $prompt = "Tôi là trợ lý ảo của website xem phim trực tuyến. Câu hỏi: \"{$question}\". " .
                "Trả lời ngắn gọn, thân thiện và hữu ích trong khoảng 2-3 câu. " .
                "Nếu là lời chào, chỉ chào đơn giản và hỏi khách muốn xem thể loại phim gì. " .
                "Nếu không có thông tin, gợi ý ngắn gọn về: phim mới, phim hot, thể loại yêu thích.";
        } else {
            $contextJson = json_encode($contextData, JSON_UNESCAPED_UNICODE);

            $prompt = "Tôi là trợ lý ảo của website xem phim trực tuyến. Câu hỏi: \"{$question}\". " .
                "Dữ liệu từ website: {$contextJson}. " .
                "Trả lời ngắn gọn, thân thiện trong 2-3 câu. " .
                "Tập trung vào thông tin chính mà khách hàng cần.";
        }

        $data = [
            "contents" => [
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ],
            "generationConfig" => [
                "temperature" => 0.7,
                "maxOutputTokens" => 200,
                "topP" => 0.8,
                "topK" => 40
            ]
        ];

        $url = $this->geminiUrl . '?key=' . $this->apiKey;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, $data);

        if ($response->successful()) {
            $responseData = $response->json();
            if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                return $responseData['candidates'][0]['content']['parts'][0]['text'];
            }
        }

        return "Xin lỗi, hệ thống đang bận. Vui lòng thử lại sau nhé!";
    }

    private function getMoviesInGenre($genreName)
    {
        $genre = TheLoai::where('ten_the_loai', 'like', '%' . $genreName . '%')
                       ->where('tinh_trang', 0)
                       ->first();

        if (!$genre) {
            $genres = TheLoai::where('tinh_trang', 0)->get();
            $genreInfo = [
                'requested_genre' => $genreName,
                'available_genres' => $genres->pluck('ten_the_loai')->toArray()
            ];

            return $this->generateGeminiResponse(
                "Bạn là nhân viên tư vấn phim. Khách hỏi về thể loại '{$genreName}' nhưng không có. " .
                    "Hãy xin lỗi và gợi ý các thể loại có sẵn: " . json_encode($genreInfo, JSON_UNESCAPED_UNICODE) . " " .
                    "Trả lời thân thiện, ngắn gọn 2-3 câu."
            );
        }

        $movies = Phim::where('id_the_loai', $genre->id)
                     ->where('trang_thai', 0)
                     ->get();

        if ($movies->isEmpty()) {
            return $this->generateGeminiResponse(
                "Bạn là nhân viên tư vấn phim. Thể loại '{$genre->ten_the_loai}' hiện không có phim nào. " .
                    "Hãy xin lỗi và gợi ý khách xem thể loại khác. Trả lời thân thiện, ngắn gọn 2 câu."
            );
        }

        $nowShowing = [];
        $comingSoon = [];
        $now = Carbon::now();

        foreach ($movies as $movie) {
            $releaseDate = Carbon::parse($movie->ngay_khoi_chieu);
            if ($releaseDate <= $now) {
                $nowShowing[] = $movie;
            } else {
                $comingSoon[] = $movie;
            }
        }

        $movieInfo = [
            'the_loai' => $genre->ten_the_loai,
            'dang_chieu' => array_map(function ($movie) {
                return [
                    'ten' => $movie->ten_phim,
                    'ngay_chieu' => Carbon::parse($movie->ngay_khoi_chieu)->format('d/m/Y')
                ];
            }, $nowShowing),
            'sap_chieu' => array_map(function ($movie) {
                return [
                    'ten' => $movie->ten_phim,
                    'ngay_chieu' => Carbon::parse($movie->ngay_khoi_chieu)->format('d/m/Y')
                ];
            }, $comingSoon)
        ];

        return $this->generateGeminiResponse(
            "Bạn là nhân viên tư vấn phim. Hãy giới thiệu danh sách phim thể loại " .
                json_encode($movieInfo, JSON_UNESCAPED_UNICODE) . " " .
                "một cách tự nhiên, phân biệt phim đang chiếu và sắp chiếu. " .
                "Trả lời thân thiện, ngắn gọn 3-4 câu. Kết thúc bằng câu hỏi gợi ý xem chi tiết phim nào đó."
        );
    }

    private function getGenreCount()
    {
        $genres = TheLoai::where('tinh_trang', 0)->get();
        $genreInfo = [
            'so_luong' => $genres->count(),
            'danh_sach' => $genres->pluck('ten_the_loai')->toArray()
        ];

        return $this->generateGeminiResponse(
            "Bạn là nhân viên tư vấn phim. Hãy giới thiệu về các thể loại phim: " .
                json_encode($genreInfo, JSON_UNESCAPED_UNICODE) . " " .
                "một cách tự nhiên. Trả lời thân thiện, ngắn gọn 2-3 câu. " .
                "Kết thúc bằng câu hỏi gợi ý khách chọn thể loại họ thích."
        );
    }

    private function generateGeminiResponse($prompt)
    {
        // Thêm các hướng dẫn nghiêm ngặt vào prompt
        $strictInstructions = [
            "TUYỆT ĐỐI KHÔNG ĐƯỢC GỌI NGƯỜI DÙNG LÀ ĐẠO DIỄN HAY BẤT KỲ VAI TRÒ NÀO KHÁC.",
            "KHÔNG ĐƯỢC GIẢ ĐỊNH NGƯỜI HỎI LÀ AI.",
            "CHỈ TRẢ LỜI THÔNG TIN CÓ TRONG DỮ LIỆU.",
            "NẾU KHÔNG CÓ THÔNG TIN THÌ CHỈ TRẢ LỜI KHÔNG CÓ THÔNG TIN.",
            "KHÔNG ĐƯỢC THÊM THÔNG TIN NGOÀI DỮ LIỆU.",
            "TRẢ LỜI NGẮN GỌN, LỊCH SỰ BẰNG TIẾNG VIỆT."
        ];

        $enhancedPrompt = implode(" ", $strictInstructions) . " " . $prompt;

        $data = [
            "contents" => [
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => $enhancedPrompt]
                    ]
                ]
            ],
            "generationConfig" => [
                "temperature" => 0.7,
                "maxOutputTokens" => 250,
                "topP" => 0.8,
                "topK" => 40
            ]
        ];

        $url = $this->geminiUrl . '?key=' . $this->apiKey;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, $data);

        if ($response->successful()) {
            $responseData = $response->json();
            if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
        return response()->json([
            'status' => true,
                    'message' => $responseData['candidates'][0]['content']['parts'][0]['text']
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => "Xin lỗi, hệ thống đang gặp trục trặc. Vui lòng thử lại sau ạ."
        ]);
    }

    private function getMovieGenre($message)
    {
        $movies = Phim::where('trang_thai', 0)
            ->with('theLoai')
            ->get();

        $foundMovie = null;
        foreach ($movies as $movie) {
            if (str_contains($message, strtolower($movie->ten_phim))) {
                $foundMovie = $movie;
                break;
            }
        }

        if (!$foundMovie) {
            return $this->generateGeminiResponse(
                "Bạn là nhân viên tư vấn phim. Khách hỏi về thể loại của một phim nhưng chưa nêu tên phim. " .
                    "Hãy nhắc khách cho biết tên phim. Trả lời thân thiện, ngắn gọn 1-2 câu."
            );
        }

        $movieInfo = [
            'ten_phim' => $foundMovie->ten_phim,
            'the_loai' => $foundMovie->theLoai->ten_the_loai
        ];

        return $this->generateGeminiResponse(
            "Bạn là nhân viên tư vấn phim. Hãy cho khách biết thể loại của phim: " .
                json_encode($movieInfo, JSON_UNESCAPED_UNICODE) . " " .
                "một cách tự nhiên. Trả lời thân thiện, ngắn gọn 2 câu. " .
                "Có thể gợi ý thêm vài phim cùng thể loại nếu có."
        );
    }

    private function getCurrentlyShowingMovies()
    {
        $movies = Phim::where('trang_thai', 0)
            ->with('theLoai')
            ->take(5)
            ->get();

        if ($movies->isEmpty()) {
            return $this->generateGeminiResponse(
                "Bạn là nhân viên tư vấn phim. Hiện không có phim nào đang chiếu. " .
                    "Hãy xin lỗi và gợi ý khách quay lại sau. Trả lời thân thiện, ngắn gọn 2 câu."
            );
        }

        $movieList = $movies->map(function ($movie) {
            return [
                'ten' => $movie->ten_phim,
                'the_loai' => $movie->theLoai->ten_the_loai
            ];
        })->toArray();

        return $this->generateGeminiResponse(
            "Bạn là nhân viên tư vấn phim. Hãy giới thiệu các phim đang chiếu: " .
                json_encode($movieList, JSON_UNESCAPED_UNICODE) . " " .
                "một cách tự nhiên. Trả lời thân thiện, ngắn gọn 3-4 câu. " .
                "Kết thúc bằng câu hỏi xem khách muốn biết thêm chi tiết về phim nào."
        );
    }

    private function getMoviesByGenre($message)
    {
        $genres = TheLoai::where('tinh_trang', 0)->get();
        $foundGenre = null;

        foreach ($genres as $genre) {
            if (str_contains($message, strtolower($genre->ten_the_loai))) {
                $foundGenre = $genre;
                break;
            }
        }

        if (!$foundGenre) {
            $genreInfo = [
                'danh_sach' => $genres->pluck('ten_the_loai')->toArray()
            ];

            return $this->generateGeminiResponse(
                "Bạn là nhân viên tư vấn phim. Khách chưa nêu rõ thể loại. Các thể loại hiện có: " .
                    json_encode($genreInfo, JSON_UNESCAPED_UNICODE) . " " .
                    "Hãy gợi ý khách chọn thể loại. Trả lời thân thiện, ngắn gọn 2 câu."
            );
        }

        $movies = Phim::where('id_the_loai', $foundGenre->id)
            ->where('trang_thai', 0)
            ->get();

        if ($movies->isEmpty()) {
            return $this->generateGeminiResponse(
                "Bạn là nhân viên tư vấn phim. Thể loại '{$foundGenre->ten_the_loai}' hiện không có phim nào. " .
                    "Hãy xin lỗi và gợi ý khách xem thể loại khác. Trả lời thân thiện, ngắn gọn 2 câu."
            );
        }

        $movieInfo = [
            'the_loai' => $foundGenre->ten_the_loai,
            'danh_sach' => $movies->map(function ($movie) {
                return [
                    'ten' => $movie->ten_phim,
                    'ngay_chieu' => Carbon::parse($movie->ngay_khoi_chieu)->format('d/m/Y')
                ];
            })->toArray()
        ];

        return $this->generateGeminiResponse(
            "Bạn là nhân viên tư vấn phim. Hãy giới thiệu phim thể loại: " .
                json_encode($movieInfo, JSON_UNESCAPED_UNICODE) . " " .
                "một cách tự nhiên. Trả lời thân thiện, ngắn gọn 3 câu. " .
                "Kết thúc bằng câu hỏi gợi ý xem chi tiết phim nào đó."
        );
    }

    private function getMovieDetails($message)
    {
        // Chuẩn hóa tin nhắn
        $message = mb_strtolower($message);

        // Lấy danh sách phim đang hoạt động
        $movies = Phim::where('trang_thai', 0)
            ->with(['theLoai']) // Eager load thể loại
            ->get();

        $foundMovie = null;

        // Tìm phim trong tin nhắn
        foreach ($movies as $movie) {
            if (str_contains($message, mb_strtolower($movie->ten_phim))) {
                $foundMovie = $movie;
            }
        }

        if (!$foundMovie) {
            return response()->json([
                'status' => true,
                'message' => "Xin chào bạn! Rất tiếc, mình chưa tìm thấy thông tin về phim này trong hệ thống. Bạn có thể cho mình biết tên phim cụ thể bạn muốn tìm hiểu được không ạ?"
            ]);
        }

        // Tạo câu trả lời chỉ với thông tin có trong database
        $response = "Chào bạn! Mình xin chia sẻ thông tin về phim '{$foundMovie->ten_phim}': ";

        $details = [];

        if (!empty($foundMovie->the_loai)) {
            $details[] = "thể loại {$foundMovie->theLoai->ten_the_loai}";
        }

        if (!empty($foundMovie->thoi_luong)) {
            $details[] = "thời lượng {$foundMovie->thoi_luong} phút";
        }

        if (!empty($foundMovie->dao_dien)) {
            $details[] = "do đạo diễn {$foundMovie->dao_dien} thực hiện";
        }

        if (!empty($foundMovie->dien_vien)) {
            $details[] = "với sự tham gia của các diễn viên: {$foundMovie->dien_vien}";
        }

        if (!empty($foundMovie->ngay_khoi_chieu)) {
            $details[] = "khởi chiếu vào ngày " . Carbon::parse($foundMovie->ngay_khoi_chieu)->format('d/m/Y');
        }

        if (!empty($foundMovie->gia_ban)) {
            $details[] = "giá vé " . number_format($foundMovie->gia_ban, 0, ',', '.') . " VNĐ";
        }

        if (count($details) > 0) {
            $response .= implode(", ", $details) . ". ";
        }

        if (!empty($foundMovie->mo_ta)) {
            $response .= "Nội dung phim: {$foundMovie->mo_ta}. ";
        }

        $response .= "Bạn muốn biết thêm thông tin gì về phim không ạ?";

        return response()->json([
            'status' => true,
            'message' => $response
        ]);
    }

    private function getTicketPriceInfo($message)
    {
        $normalizedMessage = mb_strtolower(trim($message), 'UTF-8');

        // Danh sách từ khóa cần loại bỏ để trích xuất tên phim
        $keywordsToRemove = [
            'xem phim',
            'phim',
            'giá',
            'giá vé',
            'hết bao nhiêu tiền',
            'bao nhiêu tiền',
            'đồng',
            'vnd',
            'mất bao nhiêu',
            'thuê phim',
            'mua phim'
        ];

        foreach ($keywordsToRemove as $keyword) {
            $normalizedMessage = str_replace(mb_strtolower($keyword, 'UTF-8'), '', $normalizedMessage);
        }

        // Chuẩn hóa và trích tên phim
        $movieName = trim($normalizedMessage);
        $searchName = $this->normalizeString($movieName);

        // Tìm kiếm phim tương ứng
        $movies = Phim::with('theLoai')->where('trang_thai', 0)->get(); // eager loading thể loại
        $foundMovie = null;
        $highestSimilarity = 0;

        foreach ($movies as $movie) {
            $normalizedTitle = $this->normalizeString($movie->ten_phim);

            if (
                mb_strtolower($movie->ten_phim, 'UTF-8') === mb_strtolower($movieName, 'UTF-8') ||
                $normalizedTitle === $searchName
            ) {
                $foundMovie = $movie;
                break;
            }

            similar_text($normalizedTitle, $searchName, $percent);
            if ($percent > $highestSimilarity && $percent > 70) {
                $highestSimilarity = $percent;
                $foundMovie = $movie;
            }
        }

        // Nếu không tìm được phim khớp
        if (!$foundMovie) {
            // Tìm phim có giá thấp nhất để tham khảo
            $cheapestMovie = $movies->min('gia_ban');
            // Tìm phim có giá cao nhất để tham khảo
            $mostExpensiveMovie = $movies->max('gia_ban');

            $info = [
                'ten_phim_yeu_cau' => trim(preg_replace('/[^\p{L}\p{N}\s]/u', '', $movieName)),
                'gia_thap_nhat' => number_format($cheapestMovie ?? 5000, 0, ',', '.'),
                'gia_cao_nhat' => number_format($mostExpensiveMovie ?? 5000, 0, ',', '.')
            ];

            return $this->generateGeminiResponse(
                "Bạn là nhân viên tư vấn phim. " .
                    "QUAN TRỌNG: Không tìm thấy phim có tên '" . $info['ten_phim_yeu_cau'] . "' trong hệ thống. " .
                    "Thông báo giá thuê/mua phim trên website dao động từ " . $info['gia_thap_nhat'] . " VNĐ đến " . $info['gia_cao_nhat'] . " VNĐ tùy phim. " .
                    "Trả lời thân thiện, ngắn gọn 2 câu. " .
                    "TUYỆT ĐỐI KHÔNG ĐƯỢC TỰ THÊM THÔNG TIN KHÔNG CÓ TRONG DỮ LIỆU."
            );
        }

        $movieInfo = [
            'ten_phim'   => $foundMovie->ten_phim,
            'gia_ban'    => number_format($foundMovie->gia_ban, 0, ',', '.'),
            'thoi_luong' => $foundMovie->thoi_luong,
            'the_loai'   => $foundMovie->theLoai->ten_the_loai ?? 'không xác định'
        ];

        return $this->generateGeminiResponse(
            "Bạn là nhân viên tư vấn phim. " .
                "QUAN TRỌNG: Hãy thông báo giá thuê/mua phim: " .
                json_encode($movieInfo, JSON_UNESCAPED_UNICODE) . " " .
                "một cách tự nhiên. " .
                "TUYỆT ĐỐI KHÔNG ĐƯỢC TỰ THÊM THÔNG TIN KHÔNG CÓ TRONG DỮ LIỆU. " .
                "Trả lời thân thiện, ngắn gọn 2-3 câu. " .
                "Có thể nhắc thêm về thời lượng và thể loại phim."
        );
    }

    /**
     * Chuẩn hóa chuỗi để so sánh
     * - Bỏ dấu
     * - Chuyển về chữ thường
     * - Bỏ khoảng trắng thừa
     */
    private function normalizeString($str)
    {
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9\s]/', '', $str);
        return trim(preg_replace('/\s+/', ' ', $str));
    }

    private function getShowtimes($message)
    {
        $movies = Phim::where('trang_thai', 0)->get();
        $foundMovie = null;

        foreach ($movies as $movie) {
            if (str_contains($message, strtolower($movie->ten_phim))) {
                $foundMovie = $movie;
                break;
            }
        }

        if (!$foundMovie) {
            return $this->generateGeminiResponse(
                "Bạn là nhân viên tư vấn phim. Khách hỏi về lịch chiếu nhưng chưa nêu tên phim. " .
                    "Hãy nhắc khách cho biết tên phim. Trả lời thân thiện, ngắn gọn 1-2 câu."
            );
        }

        $movieInfo = [
            'ten_phim' => $foundMovie->ten_phim,
            'bat_dau' => Carbon::parse($foundMovie->ngay_khoi_chieu)->format('d/m/Y'),
            'ket_thuc' => $foundMovie->ngay_ket_thuc ?
                Carbon::parse($foundMovie->ngay_ket_thuc)->format('d/m/Y') :
                'Đang cập nhật'
        ];

        return $this->generateGeminiResponse(
            "Bạn là nhân viên tư vấn phim. Hãy thông báo thông tin chi tiết của phim: " .
                json_encode($movieInfo, JSON_UNESCAPED_UNICODE) . " " .
                "một cách tự nhiên. Trả lời thân thiện, ngắn gọn 2-3 câu. " .
                "Có thể gợi ý đặt vé xem phim."
        );
    }

    private function getMoviePriceAndViews($message)
    {
        $movies = Phim::where('trang_thai', 0)->get();
        $foundMovie = null;

        foreach ($movies as $movie) {
            if (str_contains($message, strtolower($movie->ten_phim))) {
                $foundMovie = $movie;
                break;
            }
        }

        if (!$foundMovie) {
            return response()->json([
                'status' => true,
                'message' => 'Vui lòng cho biết tên phim bạn muốn xem thông tin.'
            ]);
        }

        $message = "Phim {$foundMovie->ten_phim}:<br>" .
            "- Giá: " . number_format($foundMovie->gia_ban, 0, ',', '.') . "đ<br>" .
            "- Lượt xem: " . number_format($foundMovie->luot_xem, 0, ',', '.');

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function suggestMovies(Request $request)
    {
        $movies = Phim::where('trang_thai', 0)
            ->with('theLoai')
            ->inRandomOrder()
            ->take(3)
            ->get();

        if ($movies->isEmpty()) {
            return $this->generateGeminiResponse(
                "Bạn là nhân viên tư vấn phim. Hiện không có phim nào để gợi ý. " .
                    "Hãy xin lỗi và mời khách quay lại sau. Trả lời thân thiện, ngắn gọn 2 câu."
            );
        }

        $movieList = $movies->map(function ($movie) {
            return [
                'ten' => $movie->ten_phim,
                'the_loai' => $movie->theLoai->ten_the_loai
            ];
        })->toArray();

        return $this->generateGeminiResponse(
            "Bạn là nhân viên tư vấn phim. Hãy gợi ý các phim hay đang chiếu: " .
                json_encode($movieList, JSON_UNESCAPED_UNICODE) . " " .
                "một cách tự nhiên và hấp dẫn. Trả lời thân thiện, ngắn gọn 3 câu. " .
                "Kết thúc bằng câu hỏi xem khách thích phim nào."
        );
    }

    private function getHotMovies()
    {
        try {
            $movies = Phim::where('trang_thai', 0)
                ->with('theLoai')
                ->orderBy('luot_xem', 'desc')
                ->take(2)  // Only take top 2 movies
                ->get();

            if ($movies->isEmpty()) {
                $response = $this->generateGeminiResponse(
                    "Bạn là nhân viên tư vấn phim. Hiện không có phim nào trong hệ thống. " .
                    "Hãy xin lỗi và mời khách quay lại sau. Trả lời thân thiện, ngắn gọn 2 câu."
                );

                return response()->json([
                    'status' => true,
                    'message' => $response->original['message']
                ]);
            }

            $movieList = $movies->map(function ($movie) {
                return [
                    'ten' => $movie->ten_phim,
                    'the_loai' => $movie->theLoai->ten_the_loai,
                    'luot_xem' => number_format($movie->luot_xem, 0, ',', '.'),
                    'thoi_luong' => $movie->thoi_luong,
                    'gia_ban' => number_format($movie->gia_ban, 0, ',', '.')
                ];
            })->toArray();

            $response = $this->generateGeminiResponse(
                "Bạn là nhân viên tư vấn phim. Hãy giới thiệu top phim hot nhất dựa CHÍNH XÁC theo dữ liệu sau: " .
                json_encode($movieList, JSON_UNESCAPED_UNICODE) . " " .
                "TUYỆT ĐỐI CHỈ sử dụng các thông tin được cung cấp (tên phim, thể loại, lượt xem, thời lượng, giá). " .
                "KHÔNG được thêm bất kỳ thông tin nào khác như điểm đánh giá, diễn viên... " .
                "Trả lời thân thiện, ngắn gọn 2-3 câu, tập trung vào số lượt xem. " .
                "Kết thúc bằng câu hỏi gợi ý xem chi tiết phim."
            );

            // Store the chat message after getting the response
            if (request()->has('userId') && !str_starts_with(request()->query('userId'), 'guest_')) {
                ChatMessage::create([
                    'khach_hang_id' => request()->query('userId'),
                    'message' => 'Xem phim hot',
                    'response' => $response->original['message']
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => $response->original['message']
            ]);

        } catch (Exception $e) {
            Log::error('Error getting hot movies: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Xin lỗi, đã có lỗi xảy ra khi lấy danh sách phim hot. Vui lòng thử lại sau.',
                'error' => $e->getMessage()
            ]);
        }
    }

    private function getNewMovies()
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);

        $movies = Phim::where('trang_thai', 0)
            ->where('created_at', '>=', $sevenDaysAgo)
            ->with('theLoai')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        if ($movies->isEmpty()) {
            // Lấy 3 phim hot nhất để gợi ý thay thế
            $hotMovies = Phim::where('trang_thai', 0)
                ->with('theLoai')
                ->orderBy('luot_xem', 'desc')
                ->take(3)
                ->get();

            if ($hotMovies->isEmpty()) {
                return $this->generateGeminiResponse(
                    "Bạn là nhân viên tư vấn phim. " .
                        "QUAN TRỌNG: Hiện không có phim mới nào được thêm vào trong 7 ngày qua. " .
                        "KHÔNG ĐƯỢC TỰ TẠO RA TÊN PHIM. " .
                        "Chỉ xin lỗi và mời khách quay lại sau. " .
                        "Trả lời thân thiện, ngắn gọn 1-2 câu."
                );
            }

            $hotMovieList = $hotMovies->map(function ($movie) {
                return [
                    'ten' => $movie->ten_phim,
                    'the_loai' => $movie->theLoai->ten_the_loai,
                    'luot_xem' => number_format($movie->luot_xem, 0, ',', '.')
                ];
            })->toArray();

            return $this->generateGeminiResponse(
                "Bạn là nhân viên tư vấn phim. " .
                    "QUAN TRỌNG: Hiện không có phim mới nào được thêm vào trong 7 ngày qua. " .
                    "Hãy gợi ý khách xem các phim hot sau đây: " .
                    json_encode($hotMovieList, JSON_UNESCAPED_UNICODE) . " " .
                    "TUYỆT ĐỐI CHỈ giới thiệu các phim trong danh sách, KHÔNG TỰ TẠO THÊM phim khác. " .
                    "Trả lời thân thiện, ngắn gọn 2-3 câu, nhấn mạnh số lượt xem."
            );
        }

        $movieList = $movies->map(function ($movie) {
            return [
                'ten' => $movie->ten_phim,
                'the_loai' => $movie->theLoai->ten_the_loai,
                'thoi_gian_them' => Carbon::parse($movie->created_at)->format('d/m/Y'),
                'thoi_luong' => $movie->thoi_luong,
                'gia_ban' => number_format($movie->gia_ban, 0, ',', '.')
            ];
        })->toArray();

        return $this->generateGeminiResponse(
            "Bạn là nhân viên tư vấn phim. " .
                "QUAN TRỌNG: Chỉ giới thiệu các phim mới được thêm vào trong 7 ngày qua từ danh sách sau: " .
                json_encode($movieList, JSON_UNESCAPED_UNICODE) . " " .
                "TUYỆT ĐỐI KHÔNG ĐƯỢC TỰ THÊM PHIM KHÔNG CÓ TRONG DANH SÁCH. " .
                "Nhấn mạnh về tính mới mẻ và thời gian phim được thêm vào. " .
                "Trả lời thân thiện, ngắn gọn 2-3 câu. " .
                "Kết thúc bằng câu hỏi gợi ý xem chi tiết phim."
        );
    }

    /**
     * Handle queries about specific movie attributes
     */
    private function getMovieAttribute($message, $attribute)
    {
        // Chuẩn hóa tin nhắn
        $message = mb_strtolower($message, 'UTF-8');

        // Lấy danh sách phim đang hoạt động
        $movies = Phim::where('trang_thai', 0)->get();
        $foundMovie = null;

        // Tìm phim trong tin nhắn
        foreach ($movies as $movie) {
            if (str_contains($message, mb_strtolower($movie->ten_phim, 'UTF-8'))) {
                $foundMovie = $movie;
                break;
            }
        }

        if (!$foundMovie) {
            return response()->json([
                'status' => true,
                'message' => "Xin chào bạn! Rất tiếc, mình chưa tìm thấy thông tin về phim này trong hệ thống. Bạn có thể cho mình biết tên phim cụ thể bạn muốn tìm hiểu được không ạ?"
            ]);
        }

        // Kiểm tra thuộc tính có tồn tại và có giá trị không
        $attributeValue = $foundMovie->$attribute;
        if (empty($attributeValue)) {
            $friendlyAttrName = $this->getAttributeDisplayName($attribute);
            return response()->json([
                'status' => true,
                'message' => "Chào bạn! Hiện tại mình chưa có thông tin về {$friendlyAttrName} của phim '{$foundMovie->ten_phim}'. Bạn muốn tìm hiểu thông tin nào khác về phim này không ạ?"
            ]);
        }

        // Định dạng câu trả lời tùy theo thuộc tính
        $formattedValue = $this->formatAttributeValue($foundMovie, $attribute);
        $response = $this->formatFriendlyResponse($foundMovie->ten_phim, $attribute, $formattedValue);

        return response()->json([
            'status' => true,
            'message' => $response
        ]);
    }

    /**
     * Format friendly response based on attribute type
     */
    private function formatFriendlyResponse($movieName, $attribute, $value)
    {
        switch ($attribute) {
            case 'dao_dien':
                return "Chào bạn! Phim '{$movieName}' do đạo diễn {$value} thực hiện. Bạn muốn biết thêm thông tin gì về phim không ạ?";
            case 'thoi_luong':
                return "Chào bạn! Phim '{$movieName}' có thời lượng {$value}. Bạn có muốn biết thêm thông tin khác không ạ?";
            case 'dien_vien':
                return "Chào bạn! Phim '{$movieName}' có sự tham gia của các diễn viên: {$value}. Bạn muốn tìm hiểu thêm về phim không ạ?";
            case 'quoc_gia':
                return "Chào bạn! Phim '{$movieName}' là phim của {$value}. Bạn có muốn biết thông tin gì thêm không ạ?";
            case 'gia_ban':
                return "Chào bạn! Giá vé xem phim '{$movieName}' là {$value}. Mình có thể giúp bạn thêm thông tin gì không ạ?";
            case 'mo_ta':
                return "Chào bạn! Nội dung phim '{$movieName}': {$value}. Bạn muốn biết thêm thông tin gì về phim không ạ?";
            default:
                return "Chào bạn! Thông tin về {$this->getAttributeDisplayName($attribute)} của phim '{$movieName}' là: {$value}. Bạn cần tìm hiểu thêm gì không ạ?";
        }
    }

    /**
     * Get display name for movie attributes
     */
    private function getAttributeDisplayName($attribute)
    {
        $displayNames = [
            'trailer' => 'trailer',
            'thoi_luong' => 'thời lượng',
            'dao_dien' => 'đạo diễn',
            'dien_vien' => 'diễn viên',
            'quoc_gia' => 'quốc gia sản xuất',
            'ngay_khoi_chieu' => 'ngày khởi chiếu',
            'ngay_ket_thuc' => 'ngày kết thúc chiếu',
            'mo_ta' => 'nội dung',
            'gia_ban' => 'giá vé'
        ];

        return $displayNames[$attribute] ?? $attribute;
    }

    /**
     * Format attribute value for display
     */
    private function formatAttributeValue($movie, $attribute)
    {
        switch ($attribute) {
            case 'ngay_khoi_chieu':
            case 'ngay_ket_thuc':
                return $movie->$attribute ? Carbon::parse($movie->$attribute)->format('d/m/Y') : 'Đang cập nhật';
            case 'gia_ban':
                return number_format($movie->gia_ban, 0, ',', '.') . ' VNĐ';
            case 'thoi_luong':
                return $movie->thoi_luong . ' phút';
            default:
                return $movie->$attribute ?: 'Đang cập nhật';
        }
    }

    public function checkBalance(Request $request)
    {
        try {
            $userId = $request->query('userId');

            // Check if user is logged in (not a guest)
            if (strpos($userId, 'guest_') === 0) {
                return $this->generateGeminiResponse(
                    "Bạn là nhân viên tư vấn. " .
                    "QUAN TRỌNG: Khách chưa đăng nhập. " .
                    "Hãy nhắc khách đăng nhập để kiểm tra số dư. " .
                    "Trả lời thân thiện, ngắn gọn 1-2 câu."
                );
            }

            $customer = KhachHang::find($userId);

            if (!$customer) {
                return $this->generateGeminiResponse(
                    "Bạn là nhân viên tư vấn. " .
                    "QUAN TRỌNG: Không tìm thấy thông tin khách hàng. " .
                    "Hãy xin lỗi và đề nghị khách thử lại. " .
                    "Trả lời thân thiện, ngắn gọn 1-2 câu."
                );
            }

            $balanceInfo = [
                'ho_va_ten' => $customer->ho_va_ten,
                'so_du' => number_format($customer->so_du, 0, ',', '.') . ' VNĐ'
            ];

            // Store the chat message
            ChatMessage::create([
                'khach_hang_id' => $userId,
                'message' => 'Kiểm tra số dư',
                'response' => "Số dư của bạn là: " . $balanceInfo['so_du']
            ]);

            return $this->generateGeminiResponse(
                "Bạn là nhân viên tư vấn tài chính. " .
                "QUAN TRỌNG: Hãy thông báo số dư cho khách hàng: " .
                json_encode($balanceInfo, JSON_UNESCAPED_UNICODE) . " " .
                "Trả lời thân thiện, ngắn gọn 2 câu. " .
                "Có thể gợi ý nạp thêm tiền nếu số dư thấp (dưới 50,000 VNĐ)."
            );

        } catch (Exception $e) {
            Log::error('Error checking balance: ' . $e->getMessage());
            return $this->generateGeminiResponse(
                "Bạn là nhân viên tư vấn. " .
                "QUAN TRỌNG: Hệ thống đang gặp lỗi. " .
                "Hãy xin lỗi và đề nghị khách thử lại sau hoặc kiểm tra trên website. " .
                "Trả lời thân thiện, ngắn gọn 1-2 câu."
            );
        }
    }

    public function getDepositHistory(Request $request)
    {
        try {
            $userId = $request->query('userId');

            // Check if user is logged in (not a guest)
            if (strpos($userId, 'guest_') === 0) {
                $response = $this->generateGeminiResponse(
                    "Bạn là nhân viên tư vấn. " .
                    "QUAN TRỌNG: Khách chưa đăng nhập. " .
                    "Hãy nhắc khách đăng nhập để xem lịch sử nạp tiền. " .
                    "Trả lời thân thiện, ngắn gọn 1-2 câu."
                );

                return response()->json([
                    'status' => false,
                    'message' => $response->original['message']
                ]);
            }

            $customer = KhachHang::find($userId);

            if (!$customer) {
                $response = $this->generateGeminiResponse(
                    "Bạn là nhân viên tư vấn. " .
                    "QUAN TRỌNG: Không tìm thấy thông tin khách hàng. " .
                    "Hãy xin lỗi và đề nghị khách thử lại. " .
                    "Trả lời thân thiện, ngắn gọn 1-2 câu."
                );

                return response()->json([
                    'status' => false,
                    'message' => $response->original['message']
                ]);
            }

            $history = TaiChinh::select(
                    'nhan_viens.ho_va_ten as hoten_nv',
                    'khach_hangs.ho_va_ten as hoten_kh',
                    'khach_hangs.email',
                    'tai_chinhs.so_tien_nap',
                    'tai_chinhs.kieu_nap',
                    'tai_chinhs.noi_dung',
                    'tai_chinhs.created_at'
                )
                ->join('khach_hangs', 'khach_hangs.id', 'tai_chinhs.id_khach_hang')
                ->join('nhan_viens', 'nhan_viens.id', 'tai_chinhs.id_nhan_vien')
                ->where('tai_chinhs.id_khach_hang', $userId)
                ->where('tai_chinhs.is_thanh_toan', 1)
                ->orderBy('tai_chinhs.created_at', 'desc')
                ->get();

            if ($history->isEmpty()) {
                $response = $this->generateGeminiResponse(
                    "Bạn là nhân viên tư vấn tài chính. " .
                    "QUAN TRỌNG: Khách hàng " . $customer->ho_va_ten . " chưa có lịch sử nạp tiền. " .
                    "Hãy thông báo và gợi ý cách nạp tiền. " .
                    "Trả lời thân thiện, ngắn gọn 2-3 câu."
                );

                return response()->json([
                    'status' => true,
                    'message' => $response->original['message']
                ]);
            }

            $historyData = $history->map(function ($item) {
                return [
                    'thoi_gian' => Carbon::parse($item->created_at)->format('d/m/Y H:i'),
                    'so_tien' => number_format($item->so_tien_nap, 0, ',', '.') . ' VNĐ',
                    'noi_dung' => $item->noi_dung,
                    'kieu_nap' => $item->kieu_nap == 1 ? 'Nạp qua ngân hàng' : 'Nạp trực tiếp'
                ];
            })->toArray();

            // Store the chat message
            ChatMessage::create([
                'khach_hang_id' => $userId,
                'message' => 'Xem lịch sử nạp tiền',
                'response' => 'Đã hiển thị lịch sử nạp tiền'
            ]);

            $summaryData = [
                'ho_va_ten' => $customer->ho_va_ten,
                'tong_giao_dich' => count($history),
                'giao_dich_gan_nhat' => $historyData[0] ?? null,
                'lich_su' => array_slice($historyData, 0, 5) // Chỉ lấy 5 giao dịch gần nhất
            ];

            $response = $this->generateGeminiResponse(
                "Bạn là nhân viên tư vấn tài chính. " .
                "QUAN TRỌNG: Hãy tổng hợp lịch sử nạp tiền cho khách hàng: " .
                json_encode($summaryData, JSON_UNESCAPED_UNICODE) . " " .
                "Trả lời thân thiện, ngắn gọn 3-4 câu. " .
                "Nêu rõ số lượng giao dịch, thời gian và số tiền giao dich gần nhất. " .
                "Nếu có nhiều hơn 5 giao dịch, gợi ý khách truy cập website để xem đầy đủ."
            );

            return response()->json([
                'status' => true,
                'message' => $response->original['message']
            ]);

        } catch (Exception $e) {
            Log::error('Error getting deposit history: ' . $e->getMessage());
            $response = $this->generateGeminiResponse(
                "Bạn là nhân viên tư vấn. " .
                "QUAN TRỌNG: Hệ thống đang gặp lỗi khi lấy lịch sử nạp tiền. " .
                "Hãy xin lỗi và đề nghị khách thử lại sau hoặc kiểm tra trên website. " .
                "Trả lời thân thiện, ngắn gọn 2 câu."
            );

            return response()->json([
                'status' => false,
                'message' => $response->original['message']
            ]);
        }
    }

    public function getPurchaseHistory(Request $request)
    {
        try {
            $userId = $request->query('userId');

            // Check if user is logged in (not a guest)
            if (strpos($userId, 'guest_') === 0) {
                $response = $this->generateGeminiResponse(
                    "Bạn là nhân viên tư vấn. " .
                    "QUAN TRỌNG: Khách chưa đăng nhập. " .
                    "Hãy nhắc khách đăng nhập để xem lịch sử mua phim. " .
                    "Trả lời thân thiện, ngắn gọn 1-2 câu."
                );

                return response()->json([
                    'status' => false,
                    'message' => $response->original['message']
                ]);
            }

            $customer = KhachHang::find($userId);

            if (!$customer) {
                $response = $this->generateGeminiResponse(
                    "Bạn là nhân viên tư vấn. " .
                    "QUAN TRỌNG: Không tìm thấy thông tin khách hàng. " .
                    "Hãy xin lỗi và đề nghị khách thử lại. " .
                    "Trả lời thân thiện, ngắn gọn 1-2 câu."
                );

                return response()->json([
                    'status' => false,
                    'message' => $response->original['message']
                ]);
            }

            $history = ChiTietPhim::select(
                    'phims.ten_phim',
                    'chi_tiet_phims.so_tien_mua',
                    'chi_tiet_phims.created_at',
                    'the_loais.ten_the_loai'
                )
                ->join('phims', 'phims.id', 'chi_tiet_phims.id_phim')
                ->join('the_loais', 'the_loais.id', 'phims.id_the_loai')
                ->where('chi_tiet_phims.id_khach_hang', $userId)
                ->orderBy('chi_tiet_phims.created_at', 'desc')
                ->get();

            if ($history->isEmpty()) {
                $response = $this->generateGeminiResponse(
                    "Bạn là nhân viên tư vấn. " .
                    "QUAN TRỌNG: Khách hàng " . $customer->ho_va_ten . " chưa có lịch sử mua phim nào. " .
                    "Hãy thông báo và gợi ý một số phim hay đang chiếu. " .
                    "Trả lời thân thiện, ngắn gọn 2-3 câu."
                );

                return response()->json([
                    'status' => true,
                    'message' => $response->original['message']
                ]);
            }

            $historyData = $history->map(function ($item) {
                return [
                    'ten_phim' => $item->ten_phim,
                    'the_loai' => $item->ten_the_loai,
                    'thoi_gian' => Carbon::parse($item->created_at)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i'),
                    'so_tien' => number_format($item->so_tien_mua, 0, ',', '.') . ' VNĐ'
                ];
            })->toArray();

            // Store the chat message
            ChatMessage::create([
                'khach_hang_id' => $userId,
                'message' => 'Xem lịch sử mua phim',
                'response' => 'Đã hiển thị lịch sử mua phim'
            ]);

            $summaryData = [
                'ho_va_ten' => $customer->ho_va_ten,
                'tong_phim' => count($history),
                'mua_gan_nhat' => $historyData[0] ?? null,
                'lich_su' => array_slice($historyData, 0, 5) // Chỉ lấy 5 giao dịch gần nhất
            ];

            $response = $this->generateGeminiResponse(
                "Bạn là nhân viên tư vấn. " .
                "QUAN TRỌNG: Hãy tổng hợp lịch sử mua phim cho khách hàng: " .
                json_encode($summaryData, JSON_UNESCAPED_UNICODE) . " " .
                "Trả lời thân thiện, ngắn gọn 3-4 câu. " .
                "Nêu rõ số lượng phim đã mua, phim mua gần nhất và thời gian. " .
                "Nếu có nhiều hơn 5 phim, gợi ý khách truy cập website để xem đầy đủ."
            );

            return response()->json([
                'status' => true,
                'message' => $response->original['message']
            ]);

        } catch (Exception $e) {
            Log::error('Error getting purchase history: ' . $e->getMessage());
            $response = $this->generateGeminiResponse(
                "Bạn là nhân viên tư vấn. " .
                "QUAN TRỌNG: Hệ thống đang gặp lỗi khi lấy lịch sử mua phim. " .
                "Hãy xin lỗi và đề nghị khách thử lại sau hoặc kiểm tra trên website. " .
                "Trả lời thân thiện, ngắn gọn 2 câu."
            );

            return response()->json([
                'status' => false,
                'message' => $response->original['message']
            ]);
        }
    }
}
