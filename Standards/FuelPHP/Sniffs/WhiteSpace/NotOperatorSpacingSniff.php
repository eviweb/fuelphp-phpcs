<?php

/**
 * FuelPHP_Sniffs_WhiteSpace_NotOperatorSpacingSniff.
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
 * FuelPHP_Sniffs_WhiteSpace_NotOperatorSpacingSniff.
 *
 * check that ! has spaces around
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Eric VILLARD <dev@eviweb.fr>
 * @copyright 2012 Eric VILLARD <dev@eviweb.fr>
 * @license   http://opensource.org/licenses/MIT MIT License
 * @version   Release: 1.0.0
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class FuelPHP_Sniffs_WhiteSpace_NotOperatorSpacingSniff 
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
            T_BOOLEAN_NOT
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
        if ($tokens[$stackPtr]['code'] === T_BOOLEAN_NOT)
        {
            if ($tokens[$stackPtr-1]['code'] !== T_WHITESPACE)
            {
                $error = 'A space need to be set before the ! operator.';
                $phpcsFile->addError($error, $stackPtr, 'SpaceBeforeNotOperator');
            }
            if ($tokens[$stackPtr+1]['code'] !== T_WHITESPACE)
            {
                $error = 'A space need to be set after the ! operator.';
                $phpcsFile->addError($error, $stackPtr, 'SpaceAfterNotOperator');
            }
        }        
    }
}
