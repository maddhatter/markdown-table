<?php namespace MaddHatter\MarkdownTable;

use Illuminate\Contracts\Support\Renderable;

class Builder implements Renderable
{

    protected $headers = [];

    protected $alignments = [];

    protected $rows = [];


    /**
     * Set column headers
     *
     * @param array $headers
     * @return $this
     */
    public function headers($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Set column alignment
     *
     * @param $alignments
     * @return $this
     */
    public function align($alignments = null)
    {
        $this->alignments = $alignments;

        return $this;
    }

    /**
     * Add one row to the table
     *
     * @param $row
     * @return $this
     */
    public function row($row)
    {
        $this->rows[] = $row;

        return $this;
    }

    /**
     * Add multiple rows
     *
     * @param $rows
     * @return $this
     */
    public function rows($rows)
    {
        foreach ($rows as $row) {
            $this->row($row);
        }

        return $this;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $widths = $this->calculateWidths();

        $table = $this->renderHeaders($widths);
        $table .= $this->renderRows($widths);

        return $table;
    }

    protected function renderHeaders($widths)
    {
        $result = '| ';
        for ($i = 0; $i < count($this->headers); $i++) {
            $result .= $this->renderCell($this->headers[$i], $this->columnAlign($i), $widths[$i]) . ' | ';
        }

        $result = rtrim($result, ' ') . PHP_EOL . $this->renderAlignments($widths) . PHP_EOL;

        return $result;
    }

    protected function renderRows($widths)
    {
        $result = '';
        foreach ($this->rows as $row) {
            $result .= '| ';
            for ($i = 0; $i < count($row); $i++) {
                $result .= $this->renderCell($row[$i], $this->columnAlign($i), $widths[$i]) . ' | ';
            }
            $result = rtrim($result, ' ') . PHP_EOL;
        }

        return $result;
    }

    protected function renderCell($contents, $alignment, $width)
    {
        switch ($alignment) {
            case 'L':
                $type = STR_PAD_RIGHT;
                break;
            case 'C':
                $type = STR_PAD_BOTH;
                break;
            case 'R':
                $type = STR_PAD_LEFT;
                break;
        }

        return $this->mb_str_pad($contents, $width, ' ', $type);
    }

    protected function calculateWidths()
    {
        $widths = [];

        foreach (array_merge([$this->headers], $this->rows) as $row) {
            for ($i = 0; $i < count($row); $i++) {
                $iWidth = mb_strlen((string)$row[$i]);
                if (( ! array_key_exists($i, $widths)) || $iWidth > $widths[$i]) {
                    $widths[$i] = $iWidth;
                }
            }
        }

        // all columns must be at least 3 wide for the markdown to work
        $widths = array_map(function ($width) {
            return $width >= 3 ? $width : 3;
        }, $widths);


        return $widths;
    }

    protected function renderAlignments($widths)
    {
        $row = '|';
        for ($i = 0; $i < count($widths); $i++) {
            $cell  = str_repeat('-', $widths[$i] + 2);
            $align = $this->columnAlign($i);

            if ($align == 'C') {
                $cell = ':' . substr($cell, 2) . ':';
            }

            if ($align == 'R') {
                $cell = substr($cell, 1) . ':';
            }

            $row .= $cell . '|';
        }

        return $row;
    }

    protected function columnAlign($columnNumber)
    {
        $valid = ['L', 'C', 'R'];

        if (array_key_exists($columnNumber, $this->alignments) && in_array($this->alignments[$columnNumber], $valid)) {
            return $this->alignments[$columnNumber];
        }

        return 'L';
    }

    protected function mb_str_pad($input, $pad_length, $pad_string, $pad_style, $encoding = "UTF-8") {
        return str_pad(
            $input,
            strlen($input) - mb_strlen($input, $encoding) + $pad_length,
            $pad_string,
            $pad_style
        );
    }
}
