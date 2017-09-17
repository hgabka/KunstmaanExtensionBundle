<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanExtensionBundle\Translation\Extraction\File;

use JMS\TranslationBundle\Annotation\Desc;
use JMS\TranslationBundle\Annotation\Ignore;
use JMS\TranslationBundle\Annotation\Meaning;
use JMS\TranslationBundle\Model\FileSource;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Model\MessageCatalogue;
use JMS\TranslationBundle\Translation\Extractor\File\DefaultPhpFileExtractor;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use Symfony\Component\Yaml\Parser;

/**
 * Class KunstmaanExtractor.
 *
 * Kunstmaan specific translation extractor:
 *  - custom class and methods (self::enterNode())
 *  - page part names from config ymls (self::visitFile())
 *
 *
 * @see \JMS\TranslationBundle\JMSTranslationBundle
 */
class KunstmaanExtractor extends DefaultPhpFileExtractor
{
    const DOMAIN = 'messages';

    /**
     * @var Parser
     */
    protected $ymlParser;

    public function enterNode(Node $node)
    {
        /**
         * Configuration:
         *  Key: name of class or method
         *  Value: the translation ID number from arguments (starting from 0!).
         */
        $classConfigs = [
            'SimpleListAction' => 1,
            'Tab' => 0,
        ];
        $methodConfigs = [
            'addField' => 1,
            'addFilter' => 2,
        ];

        if (!$node instanceof Node\Expr\New_
            && !$node instanceof Node\Expr\MethodCall
            && !$node instanceof Node\Stmt\ClassMethod
        ) {
            return;
        }

        // @todo (Chris) Ezt még meg kellene csinálni, hogy működjön!
        $ignore = false;
        $desc = $meaning = null;
//        if (null !== $docComment = $this->getDocCommentForNode($node)) {
//            if ($docComment instanceof Doc) {
//                $docComment = $docComment->getText();
//            }
//            foreach ($this->docParser->parse($docComment, 'file ' . $this->file . ' near line ' . $node->getLine()) as $annot) {
//                if ($annot instanceof Ignore) {
//                    $ignore = true;
//                } elseif ($annot instanceof Desc) {
//                    $desc = $annot->text;
//                } elseif ($annot instanceof Meaning) {
//                    $meaning = $annot->text;
//                }
//            }
//        }

        if ($ignore) {
            return;
        }

        if ($node instanceof Node\Expr\New_ && $node->class instanceof Node\Name) {
            // @todo (Chris) A parts-nál meg kellene nézni, hogy mi van, ha van namespace! Pl: new Namespace\User()...
            // @todo (Chris) Az sem ártana, ha FQNS-re tesztelné az osztály egyezőséget
            $className = $node->class->parts[0];
            if (array_key_exists($className, $classConfigs)) {
                $argumentNumber = $classConfigs[$className];
                $this->registerArgument($node, $argumentNumber, $desc, $meaning);
            }

            return;
        }
        // @todo (Chris) Ez jó lenne, ha csak megadott osztályok esetén futna le, így szűrni kellene fájlnévre, vagy osztályra.
        if ($node instanceof Node\Expr\MethodCall && is_string($node->name)) {
            $methodName = $node->name;
            if (array_key_exists($methodName, $methodConfigs)) {
                $argumentNumber = $methodConfigs[$methodName];
                $this->registerArgument($node, $argumentNumber, $desc, $meaning);
            }

            return;
        }
        /*
         * A getPossibleChildTypes() függvény válaszából gyűjti ki a Page neveket. Eg:
         * <code>
         *      public function getPossibleChildTypes()
         *      {
         *          return [
         *              [
         *                  'name'  => 'ContentPage', # <-- Ez kell nekünk
         *                  'class' => 'BssOil\PublicBundle\Entity\Pages\ContentPage'
         *              ],
         *              [
         *                  'name' => 'FormPage',
         *                  'class' => 'BssOil\PublicBundle\Entity\Pages\FormPage'
         *              ],
         *              [
         *                  'name' => 'ArticleListPage',
         *                  'class' => 'BssOil\PublicBundle\Entity\Pages\ArticleListPage'
         *              ],
         *          ];
         *      }
         * </code>
         */
        if ($node instanceof Node\Stmt\ClassMethod
            && is_string($node->name)
            && 'getPossibleChildTypes' === $node->name
            // Interface esetén ez üres.
            && $node->getStmts()
        ) {
            foreach ($node->getStmts() as $stmt) {
                if ($stmt instanceof Node\Stmt\Return_ && $stmt->expr instanceof Node\Expr\Array_) {
                    /** @var Node\Expr\Array_ $arrayNode */
                    $arrayNode = $stmt->expr;
                    /** @var Node\Expr\ArrayItem $arrayItem */
                    foreach ($arrayNode->items as $arrayItem) {
                        if ($arrayItem->value instanceof Node\Expr\Array_) {
                            /** @var Node\Expr\ArrayItem $subArrayItem */
                            foreach ($arrayItem->value->items as $subArrayItem) {
                                if ($subArrayItem->key instanceof Node\Scalar\String_
                                    && 'name' === $subArrayItem->key->value
                                    && $subArrayItem->value instanceof Node\Scalar\String_
                                ) {
                                    $id = $subArrayItem->value->value;
                                    $domain = self::DOMAIN;

                                    $message = new Message($id, $domain);
                                    $message->setDesc($desc);
                                    $message->setMeaning($meaning);
                                    $message->addSource(new FileSource((string) $this->file, $subArrayItem->getLine()));

                                    $this->catalogue->add($message);
                                }
                            }
                        }
                    }
                }
            }
        }

        /*
         * A getPossibleChildTypes() függvény válaszából gyűjti ki a Page neveket. Eg:
         * <code>
         *      public function getSearchType()
         *      {
         *          return 'search_product';
         *      }
         * </code>
         */
        if ($node instanceof Node\Stmt\ClassMethod
            && is_string($node->name)
            && 'getSearchType' === $node->name
            // Interface esetén ez üres.
            && $node->getStmts()
        ) {
            foreach ($node->getStmts() as $stmt) {
                if ($stmt instanceof Node\Stmt\Return_ && $stmt->expr instanceof Node\Scalar\String_) {
                    $id = $stmt->expr->value;
                    $domain = self::DOMAIN;

                    $message = new Message($id, $domain);
                    $message->setDesc($desc);
                    $message->setMeaning($meaning);
                    $message->addSource(new FileSource((string) $this->file, $stmt->getLine()));

                    $this->catalogue->add($message);
                }
            }
        }
    }

    /**
     * Collect the pagepart names!
     *
     * @param \SplFileInfo     $file
     * @param MessageCatalogue $catalogue
     */
    public function visitFile(\SplFileInfo $file, MessageCatalogue $catalogue)
    {
        if ('yml' !== $file->getExtension()) {
            return;
        }
        $path = strtr($file->getRealPath(), DIRECTORY_SEPARATOR, '/');
        if (false === strpos($path, 'Resources/config/pageparts')) {
            return;
        }
        $parser = $this->getYmlParser();
        $pagePartConfigs = $parser->parse(file_get_contents($file));
        if (array_key_exists('types', $pagePartConfigs) && is_array($pagePartConfigs['types'])) {
            foreach ($pagePartConfigs['types'] as $type) {
                if (is_array($type) && array_key_exists('name', $type)) {
                    $message = new Message($type['name']);
                    $message->addSource(new FileSource((string) $file));
                    $catalogue->add($message);
                }
            }
        }
    }

    /**
     * @param Node|Node\Expr\MethodCall|Node\Expr\New_ $node
     * @param $argumentNumber
     * @param $desc
     * @param $meaning
     */
    protected function registerArgument(Node $node, $argumentNumber, $desc, $meaning)
    {
        if (array_key_exists($argumentNumber, $node->args)) {
            $argument = $node->args[$argumentNumber];
            if ($argument->value instanceof Node\Scalar\String_) {
                $id = $argument->value->value;
                $domain = self::DOMAIN;

                $message = new Message($id, $domain);
                $message->setDesc($desc);
                $message->setMeaning($meaning);
                $message->addSource(new FileSource((string) $this->file, $node->getLine()));

                $this->catalogue->add($message);
            }
        }
    }

    protected function getYmlParser()
    {
        if (!$this->ymlParser) {
            $this->ymlParser = new Parser();
        }

        return $this->ymlParser;
    }
}
