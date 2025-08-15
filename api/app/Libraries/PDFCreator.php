<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class PDFCreator
{
    protected Dompdf $dompdf;

    /**
     * Constructor
     *
     * @param array $config
     *   Associative array of options to pass to the Dompdf constructor.
     *   The following options are supported:
     *   - paperSize: string|array
     *     The paper size to use. Can be either a string like 'A4',
     *     or an array with the width and height in mm.
     *     Defaults to 'A4'.
     *   - orientation: string
     *     The paper orientation to use. Can be either 'portrait' or 'landscape'.
     *     Defaults to 'portrait'.
     */
    public function __construct(array $config = [])
    {
        $options = new Options();
        // $options->set('isHtml5ParserEnabled', true); //@deprecated
        $options->set('isRemoteEnabled', true);

        $this->dompdf = new Dompdf($options);

        $paperSize     = $config['paperSize']     ?? 'A4';
        $orientation   = $config['orientation']   ?? 'portrait';

        $this->setPaper($paperSize, $orientation);
    }

    /**
     * Sets the paper size and orientation for the PDF document.
     *
     * @param string|array $size The paper size to use. Can be a string like 'A4'
     *                           or an array with width and height in points. 
     *                           If 'f4' is specified, it sets to F4 size.
     * @param string $orientation The paper orientation, either 'portrait' or 'landscape'.
     * 
     * @return void
     */

    protected function setPaper(string|array $size, string $orientation): void
    {
        if (is_string($size) && strtolower($size) === 'f4') {
            // Ukuran F4: 21.0 cm × 33.0 cm ≈ 595.28 pt × 934.96 pt
            $this->dompdf->setPaper([0, 0, 611.02, 934.44], $orientation);
        } else {
            $this->dompdf->setPaper($size, $orientation);
        }
    }

    /**
     * Load HTML content into the PDF document.
     *
     * @param string $html
     *   The HTML content to load into the PDF document.
     *
     * @return static
     *   The PDF creator object, for method chaining.
     */
    public function loadHTML(string $html): self
    {
        $this->dompdf->loadHtml($html);
        return $this;
    }

    /**
     * Renders the loaded HTML content into the PDF format.
     *
     * @return static
     *   The PDF creator object, for method chaining.
     */

    public function render(): self
    {
        $this->dompdf->render();
        return $this;
    }

    /**
     * Streams the generated PDF to the browser.
     *
     * @param string $filename
     *   The filename to send in the Content-Disposition header.
     *   Defaults to 'document.pdf'.
     * @param bool $attachment
     *   Whether to send the PDF as an attachment, or inline.
     *   Defaults to false.
     */
    public function stream(string $filename = 'document.pdf', bool $attachment = false): void
    {
        $this->dompdf->stream($filename, ['Attachment' => $attachment]);
    }

    /**
     * Saves the generated PDF to a file.
     *
     * @param string $path
     *   The path to save the PDF to.
     *
     * @return bool
     *   Whether the operation was successful.
     */
    public function saveTo(string $path): bool
    {
        $this->render();
        return file_put_contents($path, $this->dompdf->output()) !== false;
    }

    /**
     * Returns the generated PDF as a string.
     *
     * @return string
     *   The generated PDF as a string.
     */
    public function output(): string
    {
        $this->render();
        return $this->dompdf->output();
    }
}
