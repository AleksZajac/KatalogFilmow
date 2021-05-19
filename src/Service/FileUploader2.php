<?php
/**
 * File uploader.
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUploader2.
 */
class FileUploader2
{
    /**
     * Target directory.
     *
     * @var string
     */
    private $targetDirectory2;

    /**
     * FileUploader constructor.
     *
     * @param string $targetDirectory2 Target director
     */
    public function __construct(string $targetDirectory2)
    {
        $this->targetDirectory2 = $targetDirectory2;
    }

    /**
     * Upload file.
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file File to upload
     *
     * @return string Filename of uploaded file
     */
    public function upload(UploadedFile $file): string
    {
        $fileName = bin2hex(random_bytes(32)).'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory2(), $fileName);
        } catch (FileException $exception) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    /**
     * Getter for target directory.
     *
     * @return string Target directory
     */
    public function getTargetDirectory2(): string
    {
        return $this->targetDirectory2;
    }

    /**
     * Generates new filename.
     *
     * @param string $originalFilename Original filename
     * @param string $extension        File extension
     *
     * @return string New filename
     */
    private function generateFilename(string $originalFilename, string $extension): string
    {
        $safeFilename = transliterator_transliterate(
            'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
            $originalFilename
        );

        return $safeFilename.'-'.uniqid().'.'.$extension;
    }

}