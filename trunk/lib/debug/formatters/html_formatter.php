<?php
/**
 * File containing the debugHtmlFormatter class.
 * Slightly modifies the output of the standard ezcDebugHtmlFormatter class.
 *
 * @see ezcDebugHtmlFormatter
 */

class debugHtmlFormatter extends ezcDebugHtmlFormatter
{
    /**
     * Returns a string containing the HTML formatted output based on $writerData.
     *
     * @param array $writerData
     * @return string
     */
    public function getLog( array $writerData )
    {
        $str = "<table class='log'>\n";
        foreach ( $writerData as $w )
        {
            $color = isset( $this->verbosityColors[$w->verbosity]) ? $this->verbosityColors[$w->verbosity] : "";
            $date = date( 'Y-m-d H:i:s O', $w->datetime );
            $str .= <<<ENDT
<tr class='debugheader'>
    <td class='source'>
        <span class='verbosity{$w->verbosity}'>{$w->verbosity}: {$w->source}</span>
    </td>
    <td class='date'>{$date}</td>
</tr>
<tr class='debugbody'>
    <td colspan='2'><pre>{$w->message}</pre></td>
</tr>
ENDT;
            if ( isset( $w->stackTrace ) )
            {
                $str .= "<tr class='debugstacktrace'>";
                $str .= "<td colspan='2'>";
                $str .= $this->formatStackTrace( $w->stackTrace );
                $str .= "</td>";
                $str .= "</tr>";
            }
        }
        $str .= "</table>\n";

        return $str;
    }
}
?>
