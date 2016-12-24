<?php
namespace ANSR\Core\Annotation\Parser;


use ANSR\Core\Annotation\AnnotationInterface;

/**
 * @author Ivan Yonkov <ivanynkv@gmail.com>
 */
class AnnotationParser implements AnnotationParserInterface
{
    const TOKEN_USE = 'use';

    /**
     * @param \ReflectionClass|\ReflectionMethod $target
     * @return AnnotationInterface[]
     */
    public function parse($target)
    {
        $document = $target->getDocComment();
        $pattern = "/\*\s*@.*/";
        preg_match_all($pattern, $document, $matches);
        $annotationsStrings = $matches[0];
        $annotations = [];

        foreach ($annotationsStrings as $annotationsString) {
            $tokens = explode("@", $annotationsString);
            $annotationParams = $tokens[1];
            $annotationParamsTokens = explode("(", $annotationParams);
            $annotationName = trim($annotationParamsTokens[0]);
            $properties = [AnnotationTokenInterface::CONSTRUCTOR_VALUE => ''];

            if (!class_exists($annotationName)) {
                $found = false;
                $fileName = $target->getFileName();
                $handle = fopen($fileName, "r");

                if ($handle) {
                    while (($line = fgets($handle)) !== false) {
                        $line = trim($line);
                        if (strpos($line, self::TOKEN_USE) === 0) {
                            $lineTokens = explode(" ", $line);
                            $annotationPath = trim($lineTokens[1], ';');

                            if ($annotationName == basename($annotationPath)) {
                                $annotationName = $annotationPath;
                                $found = true;
                                break;
                            }
                        }
                    }

                    fclose($handle);
                }

                if (!$found) {
                    continue;
                }
            }

            if (count($annotationParamsTokens) > 1) {
                $propertyInfo = $annotationParamsTokens[1];
                preg_match_all("/([a-zA-Z]+)=\"(.*?)\"/", $propertyInfo, $matches);
                if (!empty($matches[1])) {
                    foreach ($matches[1] as $i => $propertyKey) {
                        $propertyValue = $matches[2][$i];
                        $properties[$propertyKey] = $propertyValue;
                    }

                    if (strpos($propertyInfo, '"') === 0) {
                        $value = trim($propertyInfo, '"');
                        $value = substr($value, 0, strpos($value, '"'));
                        $properties[AnnotationTokenInterface::CONSTRUCTOR_VALUE] = $value;
                    }
                } else {
                    $value = trim($propertyInfo, '"');
                    $value = substr($value, 0, strpos($value, '")'));
                    $properties[AnnotationTokenInterface::CONSTRUCTOR_VALUE] = $value;
                }


            }

            $token = new DefaultAnnotationToken($annotationName, $properties);

            $builder = $token->getBuilder($target);
            foreach ($properties as $key => $value) {
                $builder = $builder->setProperty($key, $value);
            }

            $annotations[basename($annotationName)] = $builder->build();
        }

        return $annotations;
    }
}