<?php

namespace OlegV;

use Monolog\Level;
use Monolog\Formatter\LineFormatter;
use Monolog\LogRecord;

class Logdye extends LineFormatter
{
    private const RESET = "\033[0m";

    /**
     * @param  string|null  $format  The format of the message
     * @param  string|null  $dateFormat  The format of the timestamp: one supported by DateTime::format
     * @param  bool  $allowInlineLineBreaks  Whether to allow inline line breaks in log entries
     * @param  bool  $ignoreEmptyContextAndExtra
     *     Only useful if no %color_start%/%color_end% specified in $format
     */
    public function __construct(
        ?string $format = LineFormatter::SIMPLE_FORMAT,
        ?string $dateFormat = null,
        bool $allowInlineLineBreaks = false,
        bool $ignoreEmptyContextAndExtra = false,
    ) {
        parent::__construct(
            $format,
            $dateFormat,
            $allowInlineLineBreaks,
            $ignoreEmptyContextAndExtra,
        );

        if (!str_contains($this->format, '%color_start%') && !str_contains($this->format, '%color_end%')) {
            $this->format = (string)preg_replace(
                '/%level_name%/',
                '%color_start%%level_name%%color_end%',
                $this->format,
            );
        }
    }

    /**
     * Formats a log record, with color.
     *
     * @param  LogRecord  $record  A record to format
     * @return string The formatted and colored record
     */
    public function format(LogRecord $record): string
    {
        $formatted = parent::format($record);
        $formatted = str_replace('%color_start%', $this->getColor($record->level->value), $formatted);
        return str_replace('%color_end%', self::RESET, $formatted);
    }

    /**
     * Colors for log level
     *
     * @param  int  $level
     * @return string
     */
    private function getColor(int $level): string
    {
        $ColorScheme = [
            Level::Debug->value => "\033[0;37m",
            Level::Info->value => "\033[1;34m",
            Level::Notice->value => "\033[1;32m",
            Level::Warning->value => "\033[0;33m",
            Level::Error->value => "\033[1;33m",
            Level::Critical->value => "\033[0;31;51m",
            Level::Alert->value => "\033[1;30;41m",
            Level::Emergency->value => "\033[1;5;21;41m",
        ];
        return $ColorScheme[$level];
    }
}