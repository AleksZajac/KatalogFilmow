<?php
/**
 * File uploader.
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUp.
 */
class FileUp
{
    /**
     * Target directory.
     *
     * @var string
     */
    private $targetDirectoryy;

    /**
     * FileUploader constructor.
     *
     * @param string $targetDirectoryy Target director
     */
    public function __construct(string $targetDirectoryy)
    {
        $this->targetDirectoryy = $targetDirectoryy;
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
            $file->move($this->getTargetDirectoryy(), $fileName);
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
    public function getTargetDirectoryy(): string
    {
        return $this->targetDirectoryy;
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