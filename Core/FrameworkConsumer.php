<?php
/**
 * Created by IntelliJ IDEA.
 * User: RoYaL
 * Date: 12/24/2016
 * Time: 1:06 AM
 */

namespace ANSR\Core;


use ANSR\Core\Container\ContainerInterface;

/**
 * @author Ivan Yonkov <ivanynkv@gmail.com>
 */
abstract class FrameworkConsumer
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function preLoadHook()
    {

    }
}