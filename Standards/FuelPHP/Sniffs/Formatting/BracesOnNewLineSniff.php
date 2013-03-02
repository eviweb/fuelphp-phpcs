<?php

/**
 * FuelPHP_Sniffs_Formatting_BracesOnNewLineSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Eric VILLARD <dev@eviweb.fr>
 * @copyright 2012 Eric VILLARD <dev@eviweb.fr>
 * @license   http://opensource.org/licenses/MIT MIT License
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * FuelPHP_Sniffs_Formatting_BracesOnNewLineSniff.
 *
 * check that each brace of a control structure is on a new line
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Eric VILLARD <dev@eviweb.fr>
 * @copyright 2012 Eric VILLARD <dev@eviweb.fr>
 * @license   http://opensource.org/licenses/MIT MIT License
 * @version   Release: 1.0.0
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class FuelPHP_Sniffs_Formatting_BracesOnNewLineSniff 
    implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
        'PHP',
        'JS',
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_IF,
            T_ELSE,
            T_ELSEIF,
            T_FOR,
            T_FOREACH,
            T_WHILE,
            T_SWITCH,            
        );
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $registered = $this->register();
        // ensure we get one of the registered token
        if (in_array($tokens[$stackPtr]['code'], $registered)) {            
            if (!isset($tokens[$stackPtr]['scope_opener'])
                    || !isset($tokens[$stackPtr]['scope_closer'])) {
                return;
            }
            $column = $tokens[$stackPtr]['column'];
            $line   = $tokens[$stackPtr]['line'];
            $opener = $tokens[$stackPtr]['scope_opener'];
            $closer = $tokens[$stackPtr]['scope_closer'];
            
            // check opening braces on next line
            if ($tokens[$opener]['line'] !== ($line + 1)) {
                $error = 'Opening brace must be on next line from its condition';
                $phpcsFile->addError($error, $stackPtr, 'OpeningBraceOnNextLine');                
            }
            // check correct indentation
            if ($tokens[$opener]['column'] !== $column) {
                $error = 'Opening brace must have the same indentation than its condition';
                $phpcsFile->addError($error, $stackPtr, 'OpeningBraceWithSameIndentation');
            }
            // check eol right after
            if ($tokens[$opener+1]['content'] !== $phpcsFile->eolChar) {
                $error = 'Opening brace must be followed by EOL char';
                $phpcsFile->addError($error, $stackPtr, 'OpeningBraceFollowedByEOL');
            }
            // check closing braces on their own lines
            $alone   = true;
            $current = $closer-1;
            while ($tokens[$current]['line'] === $tokens[$closer]['line'] 
                    && $alone
                    && $current > $opener) {
                $alone = $tokens[$current]['code'] === T_WHITESPACE;
                $current--;
            }
            if ($alone !== true) {
                $error = 'Closing brace must be on next line from the content before';
                $phpcsFile->addError($error, $stackPtr, 'ClosingBraceOnNextLine');               
            }
             // check correct indentation
            if ($tokens[$closer]['column'] !== $column) {
                $error = 'Closing brace must have the same indentation than its condition';
                $phpcsFile->addError($error, $stackPtr, 'ClosingBraceWithSameIndentation');
            }
            // check eol right after
            if (isset($tokens[$closer+1])
                    && $tokens[$closer+1]['content'] !== $phpcsFile->eolChar) {
                $error = 'Closing brace must be followed by EOL char';
                $phpcsFile->addError($error, $stackPtr, 'ClosingBraceFollowedByEOL');
            }
        }        
    }
}
