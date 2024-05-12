<?php

declare(strict_types=1);

namespace ActiveCollab\Utils\Test;

use ActiveCollab\Url\Url;
use ActiveCollab\Utils\Test\Base\TestCase;

class UrlTest extends TestCase
{
    /**
     * @dataProvider produceForExtendTest
     */
    public function testWillExtend(
        string $start_url,
        array $extend_with,
        string $expected_url,
    ): void
    {
        $url = new Url($start_url);

        $this->assertSame(
            $expected_url,
            $url->getExtendedUrl($extend_with),
        );
    }

    private function produceForExtendTest(): array
    {
        return [

            // Start URL does not have query params.
            [
                'https://activecollab.com',
                [
                    'one' => 'two',
                    'three' => 'four',
                ],
                'https://activecollab.com?one=two&three=four',
            ],

            // Start URL does not have query params.
            [
                'https://activecollab.com?one=two',

                [
                    'three' => 'four',
                    'five' => 'six',
                ],
                'https://activecollab.com?one=two&three=four&five=six',
            ],

        ];
    }

    public function testWillRemoveQueryString(): void
    {
        $this->assertSame(
            'https://activecollab.com?one=two&five=six',
            (new Url('https://activecollab.com?one=two&three=four&five=six'))->removeQueryElement(
                'three',
            ),
        );
    }
}
