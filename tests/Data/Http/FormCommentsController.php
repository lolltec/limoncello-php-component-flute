<?php namespace Limoncello\Tests\Flute\Data\Http;

/**
 * Copyright 2015-2018 info@neomerx.com
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use Limoncello\Flute\Contracts\Http\Controller\ControllerCreateInterface;
use Limoncello\Flute\Http\Traits\DefaultControllerMethodsTrait;
use Limoncello\Tests\Flute\Data\Models\Comment;
use Limoncello\Tests\Flute\Data\Validation\Forms\CreateCommentRules;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * @package Limoncello\Tests\Flute
 */
class FormCommentsController implements ControllerCreateInterface
{
    use DefaultControllerMethodsTrait;

    /**
     * @inheritdoc
     */
    public static function create(
        array $routeParams,
        ContainerInterface $container,
        ServerRequestInterface $request
    ): ResponseInterface {
        $validator = static::defaultCreateFormValidator($container, CreateCommentRules::class);

        $isOk = $validator->validate([
            Comment::FIELD_TEXT => 'some text',
        ]);

        return new HtmlResponse('<!doctype html><title>.</title>', $isOk === true ? 200 : 400);
    }
}
