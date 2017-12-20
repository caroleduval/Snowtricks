<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Photo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Service\FileUploader;
use PHPUnit\Framework\TestCase;

class FileUploaderTest extends TestCase
{
    protected $tempfile;
    protected $image;
    private $photosDir;
    private $photoMock;

    public function setUp()
    {
        parent::setUp();

        $this->tempfile = tempnam($this->photosDir, 'upl'); // create file
        imagepng(imagecreatetruecolor(10, 10), $this->tempfile); // create and write photo/png to it
        $this->image = new UploadedFile(
            $this->tempfile,
            'new_image.png'
        );

    }

    public function testPreUpload()
    {
        $fu= new FileUploader($this->photosDir);
        $photo = new Photo();
        $photo->setFile($this->image);
        $fu->preUpload($photo);

        $this->assertSame('new_image.png',$photo->getAlt());
        $this->assertSame('png',$photo->getType());

    }

/*    public function testUpload()
    {
        $this->photoMock = $this->createMock(Photo::class);

        $this->photoMock->setFile($this->image);

        $this->photoMock
            ->method('getId')
            ->willReturn('999');

        $fu= new FileUploader($this->photosDir);

        $fu->preUpload($this->photoMock);

        $this->assertFileExists($this->photosDir.'999.png');
    }*/

    public function tearDown()
    {
        unlink($this->tempfile);
    }
}