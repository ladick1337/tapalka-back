<?php

namespace App\Modules\QR;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Writer\WriterInterface;

class QR
{

    protected $data;
    protected $size;

    protected $margin;
    protected $backgroundColor;
    protected $foregroundColor;

    public function __construct(string $data, int $size, int $margin = 0){
        $this->data = $data;
        $this->size = $size;
        $this->margin = $margin;
        $this->backgroundColor = new Color(255, 255, 255);
        $this->foregroundColor = new Color(0, 0, 0);
    }

    public function setBackgroundColor(int $r, int $g, int $b) : self
    {
        $this->backgroundColor = new Color($r, $g, $b);
        return $this;
    }

    public function setForegroundColor(int $r, int $g, int $b) : self
    {
        $this->foregroundColor = new Color($r, $g, $b);
        return $this;
    }

    protected function build(WriterInterface $writer) : ResultInterface
    {

        return Builder::create()
            ->writer($writer)
            ->writerOptions([])
            ->data($this->data)
            ->encoding(new Encoding('UTF-8'))
            ->size($this->size)
            ->margin($this->margin)
            ->backgroundColor($this->backgroundColor)
            ->foregroundColor($this->foregroundColor)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->build();

    }


    public function toPNG() : ResultInterface
    {
        return $this->build(new PngWriter());
    }

    public function toSVG() : ResultInterface
    {
        return $this->build(new SvgWriter());
    }


}
