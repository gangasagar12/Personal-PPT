<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Ganga Sagar Joshi Portfolio — Markdown & Article Content Parser
 *
 * Automatically converts Markdown and plain formatted text into clean,
 * semantic HTML with support for headings, paragraphs, lists, bold, italics,
 * code blocks, and blockquotes.
 */
class Markdown {

    /**
     * Parse markdown / plain text article content into clean, semantic HTML.
     */
    public static function parse(?string $text): string {
        if ($text === null || trim($text) === '') {
            return '';
        }

        // If the text already contains block-level HTML tags, return it directly
        if (preg_match('/<(?:p|h[1-6]|ul|ol|li|table|div|blockquote|pre)\b/i', $text)) {
            return $text;
        }

        // Normalize line endings
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // Split into raw blocks by 2 or more newlines
        $rawBlocks = preg_split('/\n{2,}/', trim($text));
        $output = [];

        foreach ($rawBlocks as $block) {
            $block = trim($block);
            if ($block === '') continue;

            // Fenced code block ```lang\ncode\n```
            if (preg_match('/^```(\w*)\n(.*)```$/s', $block, $m)) {
                $code = htmlspecialchars($m[2], ENT_QUOTES, 'UTF-8');
                $output[] = "<pre><code>{$code}</code></pre>";
                continue;
            }

            // Single-line Heading: # H1, ## H2, ### H3, #### H4
            if (preg_match('/^(#{1,6})\s+(.+)$/s', $block, $m) && !str_contains($m[2], "\n")) {
                $level = strlen($m[1]);
                $tag = match($level) {
                    1 => 'h2',
                    2 => 'h3',
                    3 => 'h3',
                    default => 'h4'
                };
                $output[] = "<{$tag}>" . self::parseInline($m[2]) . "</{$tag}>";
                continue;
            }

            // Check if entire block is an Unordered List
            $lines = explode("\n", $block);
            $isUnordered = true;
            $isOrdered = true;
            foreach ($lines as $line) {
                $l = trim($line);
                if ($l === '') continue;
                if (!preg_match('/^[-*+•]\s+(.+)$/u', $l)) $isUnordered = false;
                if (!preg_match('/^\d+[\.\)]\s+(.+)$/', $l)) $isOrdered = false;
            }

            if ($isUnordered && count($lines) > 0) {
                $items = [];
                foreach ($lines as $line) {
                    $l = trim($line);
                    if (preg_match('/^[-*+•]\s+(.+)$/u', $l, $m)) {
                        $items[] = '<li>' . self::parseInline($m[1]) . '</li>';
                    }
                }
                $output[] = "<ul>\n" . implode("\n", $items) . "\n</ul>";
                continue;
            }

            // Check if entire block is an Ordered List
            if ($isOrdered && count($lines) > 0) {
                $items = [];
                foreach ($lines as $line) {
                    $l = trim($line);
                    if (preg_match('/^\d+[\.\)]\s+(.+)$/', $l, $m)) {
                        $items[] = '<li>' . self::parseInline($m[1]) . '</li>';
                    }
                }
                $output[] = "<ol>\n" . implode("\n", $items) . "\n</ol>";
                continue;
            }

            // Blockquote: lines starting with >
            $isQuote = true;
            foreach ($lines as $line) {
                if (!str_starts_with(trim($line), '>')) $isQuote = false;
            }
            if ($isQuote && count($lines) > 0) {
                $quoteLines = [];
                foreach ($lines as $line) {
                    $quoteLines[] = self::parseInline(trim(preg_replace('/^>\s*/', '', trim($line))));
                }
                $output[] = '<blockquote><p>' . implode("<br>\n", $quoteLines) . '</p></blockquote>';
                continue;
            }

            // Check if block has internal headings or lists mixed together
            $hasInternalHeadersOrBullets = false;
            foreach ($lines as $l) {
                $lt = trim($l);
                if (preg_match('/^(#{1,6})\s+/', $lt) || preg_match('/^[-*+•]\s+/', $lt) || preg_match('/^\d+[\.\)]\s+/', $lt)) {
                    $hasInternalHeadersOrBullets = true;
                    break;
                }
            }

            if ($hasInternalHeadersOrBullets) {
                $subHtml = [];
                $currentList = null; // 'ul' or 'ol'
                $listItems = [];

                foreach ($lines as $line) {
                    $lt = trim($line);
                    if ($lt === '') continue;

                    if (preg_match('/^(#{1,6})\s+(.+)$/', $lt, $hm)) {
                        if ($currentList) {
                            $subHtml[] = "<{$currentList}>\n" . implode("\n", $listItems) . "\n</{$currentList}>";
                            $currentList = null;
                            $listItems = [];
                        }
                        $level = strlen($hm[1]);
                        $tag = match($level) { 1 => 'h2', 2 => 'h3', default => 'h4' };
                        $subHtml[] = "<{$tag}>" . self::parseInline($hm[2]) . "</{$tag}>";
                    } elseif (preg_match('/^[-*+•]\s+(.+)$/u', $lt, $lm)) {
                        if ($currentList !== 'ul') {
                            if ($currentList) {
                                $subHtml[] = "<{$currentList}>\n" . implode("\n", $listItems) . "\n</{$currentList}>";
                            }
                            $currentList = 'ul';
                            $listItems = [];
                        }
                        $listItems[] = '<li>' . self::parseInline($lm[1]) . '</li>';
                    } elseif (preg_match('/^\d+[\.\)]\s+(.+)$/', $lt, $om)) {
                        if ($currentList !== 'ol') {
                            if ($currentList) {
                                $subHtml[] = "<{$currentList}>\n" . implode("\n", $listItems) . "\n</{$currentList}>";
                            }
                            $currentList = 'ol';
                            $listItems = [];
                        }
                        $listItems[] = '<li>' . self::parseInline($om[1]) . '</li>';
                    } else {
                        if ($currentList) {
                            $subHtml[] = "<{$currentList}>\n" . implode("\n", $listItems) . "\n</{$currentList}>";
                            $currentList = null;
                            $listItems = [];
                        }
                        // Check for section title like "Key Highlights:" or "Step 1: Database Setup"
                        if (preg_match('/^([A-Z0-9][\w\s\-\&]+:)$/', $lt) && strlen($lt) < 60) {
                            $subHtml[] = "<h3>" . self::parseInline($lt) . "</h3>";
                        } else {
                            $subHtml[] = "<p>" . self::parseInline($lt) . "</p>";
                        }
                    }
                }
                if ($currentList) {
                    $subHtml[] = "<{$currentList}>\n" . implode("\n", $listItems) . "\n</{$currentList}>";
                }
                $output[] = implode("\n", $subHtml);
                continue;
            }

            // Normal paragraph block:
            // Check if single line is a section title ending with ":"
            if (count($lines) === 1 && preg_match('/^([A-Z0-9][\w\s\-\&]+:)$/', $block) && strlen($block) < 60) {
                $output[] = "<h3>" . self::parseInline($block) . "</h3>";
            } else {
                $pContent = [];
                foreach ($lines as $line) {
                    $pContent[] = self::parseInline(trim($line));
                }
                $output[] = '<p>' . implode("<br>\n", $pContent) . '</p>';
            }
        }

        return implode("\n\n", $output);
    }

    /**
     * Parse inline markdown (bold, italic, code, links).
     */
    public static function parseInline(string $text): string {
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        // Bold: **text** or __text__
        $text = preg_replace('/(\*\*|__)(.*?)\1/', '<strong>$2</strong>', $text);
        // Italic: *text* or _text_
        $text = preg_replace('/(?<!\*)\*(?!\*)([^\*]+)(?<!\*)\*(?!\*)/', '<em>$1</em>', $text);
        // Inline code: `code`
        $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text);
        // Links: [title](url)
        $text = preg_replace('/\[(.*?)\]\((https?:\/\/[^\s\)]+)\)/', '<a href="$2" target="_blank" rel="noopener noreferrer">$1</a>', $text);
        return $text;
    }
}
