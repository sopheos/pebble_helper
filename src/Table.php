<?php

namespace Pebble\Helpers;

/**
 * HTML Table Generating Class
 *
 * @author mathieu
 */
class Table
{

    /**
     * Data for table rows
     *
     * @var array
     */
    protected $rows = [];

    /**
     * Data for table heading
     *
     * @var array
     */
    protected $heading = [];

    /**
     * Auto heading
     *
     * @var bool
     */
    protected $auto_heading = TRUE;

    /**
     * Table caption
     *
     * @var string
     */
    protected $caption = NULL;

    /**
     * Table layout template
     *
     * @var array
     */
    protected $template = NULL;

    /**
     * Newline setting
     *
     * @var string
     */
    protected $newline = "\n";

    /**
     * Tab string
     *
     * @var string
     */
    protected $tab = "\t";

    /**
     * Contents of empty cells
     *
     * @var string
     */
    protected $empty_cells = '';

    // -------------------------------------------------------------------------

    /**
     * Set the template on construct
     *
     * @param type $template
     */
    public function __construct($template = [])
    {
        $this->template = $this->defaultTemplate();
        $this->setTemplate($template);
    }

    // -------------------------------------------------------------------------

    /**
     * Set template
     *
     * @param array $template
     * @return \Pebble\Helpers\Table
     */
    public function setTemplate(array $template)
    {
        foreach ($template as $key => $val) {
            if (is_array($val)) {
                $this->template[$key] = $val;
            }
        }
        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Clear table headings and data
     *
     * @return \Pebble\Helpers\Table
     */
    public function clear()
    {
        $this->rows    = [];
        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Clear table headings and data
     * @return \Pebble\Helpers\Table
     */
    public function clearAll()
    {
        $this->rows    = [];
        $this->heading = [];
        $this->caption = '';
        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Set "empty" cells
     *
     * @param type $value
     * @return \Pebble\Helpers\Table
     */
    public function setEmpty($value)
    {
        $this->empty_cells = $value;
        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Add a table caption
     *
     * @param string $caption
     * @return \Pebble\Helpers\Table
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Set the table heading
     *
     * @param mixed
     * @return \Pebble\Helpers\Table
     */
    public function setHeading(...$args)
    {
        $this->heading = $this->_prepareArguments($args);
        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Enable auto heading
     *
     * @return \Pebble\Helpers\Table
     */
    public function enableAutoHeading()
    {
        $this->auto_heading = TRUE;
        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Disable auto heading
     *
     * @return \Pebble\Helpers\Table
     */
    public function disableAutoHeading()
    {
        $this->auto_heading = FALSE;
        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Add row
     *
     * @param mixed
     * @return \Pebble\Helpers\Table
     */
    public function addRow(...$args)
    {
        $this->rows[] = $this->_prepareArguments($args);
        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Add rows
     *
     * @param mixed
     * @return \Pebble\Helpers\Table
     */
    public function addRows(array $rows)
    {
        foreach ($rows as $row) {
            $this->addRow($row);
        }
        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Generate the table
     *
     * @param array $rows
     * @return string
     */
    public function generate(array $rows = NULL)
    {

        // The table data can optionnaly be passed to this function
        if ($rows) {
            $this->addRows($rows);
        }

        // Default heading
        if (!$this->heading && $this->auto_heading) {
            $this->heading = array_shift($this->rows);
        }

        // There is no data
        if (!$this->rows) {
            return '';
        }

        // Heading length
        $heading_len = count($this->heading);

        // Begin to build the table
        $out = '<table' . $this->_formatAttr($this->template['table_open']) . '>' . $this->newline;

        // Caption
        if ($this->caption) {
            $out .= $this->tab . '<caption>' . $this->caption . '</caption>' . $this->newline;
        }

        // Heading
        if ($this->heading) {
            $out .= $this->tab . '<thead>' . $this->newline;
            $out .= $this->tab . $this->tab . '<tr>' . $this->newline;
            foreach ($this->heading as $heading) {
                $out .= $this->tab . $this->tab . $this->tab . $this->_formatCol($heading, 'th') . $this->newline;
            }
            $out .= $this->tab . $this->tab . '</tr>' . $this->newline;
            $out .= $this->tab . '</thead>' . $this->newline;
        }

        // Body
        $out .= $this->tab . '<tbody>' . $this->newline;
        foreach ($this->rows as $row) {
            $out .= $this->tab . $this->tab . '<tr>' . $this->newline;
            $row_len = 0;
            foreach ($row as $col) {
                if ($row_len < $heading_len) {
                    $out .= $this->tab . $this->tab . $this->tab . $this->_formatCol($col, 'td') . $this->newline;
                    $row_len++;
                }
            }
            // Empty cells
            for ($i = $row_len; $i < $heading_len; $i++) {
                $out .= $this->tab . $this->tab . $this->tab . '<td>' . $this->empty_cells . '</td>' . $this->newline;
            }

            $out .= $this->tab . $this->tab . '</tr>' . $this->newline;
        }
        $out .= $this->tab . '</tbody>' . $this->newline;

        // End
        $out .= '</table>';

        return $out;
    }

    // -------------------------------------------------------------------------

    /**
     * Default Template
     *
     * @return	array
     */
    protected function defaultTemplate()
    {
        return [
            'table_open' => [
                'class'       => 'table',
                'border'      => '0',
                'cellpadding' => '4',
                'cellspacing' => '0'
            ]
        ];
    }

    // -------------------------------------------------------------------------

    /**
     * Prepare arguments
     *
     * @param array $args
     * @return array
     */
    protected function _prepareArguments(array $args)
    {

        if (isset($args[0]) && count($args) === 1 && is_array($args[0])) {
            $args = $args[0];
        }

        $data = [];
        foreach ($args as $v) {
            $data[] = is_array($v) ? $v : ['data' => $v];
        }

        return $data;
    }

    // -------------------------------------------------------------------------

    /**
     * Format the html attributes
     *
     * @param array $attributes
     * @return string
     */
    protected function _formatAttr(array $attributes)
    {

        $attr = '';

        foreach ($attributes as $key => $val) {
            if ($key != 'data') {
                $attr .= " {$key}=\"{$val}\"";
            }
        }

        return $attr;
    }

    // -------------------------------------------------------------------------

    /**
     *
     * @param array $col
     * @param string $type
     * @return type
     */
    protected function _formatCol(array $col, $type)
    {
        return '<' . $type . $this->_formatAttr($col) . '>' . $col['data'] . '</' . $type . '>';
    }

    // -------------------------------------------------------------------------
}

/* End of file */
