<?php

namespace App\Services\Vision;

use App\Models\Embedding\File;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;

/**
 * Service for processing document images and extracting text
 * 
 * This service handles:
 * - Converting PDFs to images
 * - Extracting text from images using AI vision
 * - Managing temporary file storage
 */
class VisionService
{
    private File $file;

    /**
     * Create a new VisionService instance
     *
     * @param File $file File to process
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Convert file to image format
     *
     * @param File $file Source file
     * @param string $format Output image format
     * @return array Array of generated image paths
     */
    private function fileToImage(File $file, string $format = "jpeg") : array
    {
        $file_pdf = new \Spatie\PdfToImage\Pdf(Storage::path($file->path));
        $file_pdf->setOutputFormat($format);
        $dir = "pdf_images" . "/" . $file->team_id;
        Storage::makeDirectory($dir);
        //$file_pdf->setPage(20)->saveImage(Storage::path($dir . '/page_' . time() . '.jpg'));
        $file_pdf->saveAllPagesAsImages(Storage::path($dir));
        return Storage::allFiles($dir);
    }

    /**
     * Convert image to text using AI vision
     *
     * @param array $images Array of image paths
     * @param string $format Image format
     * @return string Extracted text content
     */
    private function imageToText(array $images, string $format = "jpeg") : string
    {
        $images_content = [
            [
                "type" => "text",
                "text" => "Extract all the data and knowledge.
                Give all specific sections, data and details.
                Return as text.
                If the image doesnt contain any useful information.
                or you are unable to extract data just return an empty text."
            ]
        ];
        foreach ($images as $image) {
            print("Processing image : " . $image . "\n");
            $image_url = "data:image/".$format.";base64,".base64_encode(file_get_contents(Storage::path($image)));
            $images_content[] = [
                "type" => "image_url",
                "image_url" => ["url" => $image_url]
            ];
            Storage::delete($image);
        }
        $response = OpenAI::chat()->create([
            "model" => "gpt-4o-mini",
            "messages" => [
                [
                    "role" => "user",
                    "content" => $images_content
                ],
            ],
            "max_tokens" => 300
        ]);
        $merged_data = "";
        foreach ($response["choices"] as $chunk) {
            print("Chunk : " . $chunk["message"]["content"] . "\n");
            $merged_data .= $chunk["message"]["content"] . "\n";
        }
        return $merged_data;
    }

    /**
     * Process file and extract text content
     *
     * @param string $format Output image format
     * @return string Extracted text content
     */
    public function create(string $format = "jpeg") : string {
        $image_file = $this->fileToImage($this->file, $format);
        $text_file = $this->imageToText($image_file, $format);
        return $text_file;
    }

}
