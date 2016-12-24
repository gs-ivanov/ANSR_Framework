<?php

namespace ANSR\Core\Annotation\Builder;


use ANSR\Core\Annotation\AnnotationInterface;

interface AnnotationBuilderInterface
{
    public function setProperty($property, $value): AnnotationBuilderInterface;

    public function build(): AnnotationInterface;
}