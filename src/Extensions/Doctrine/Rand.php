<?php

namespace App\Extensions\Doctrine;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\QueryException;

class Rand extends \Doctrine\ORM\Query\AST\Functions\FunctionNode
{

    /**
     * @throws QueryException
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'RAND()';
    }
}