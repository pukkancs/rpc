# PayBreak/RPC

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

## Install

Via Composer

``` bash
$ composer require paybreak/rpc
```

## Usage

``` php
class MyApi
{
    use \PayBreak\Rpc\Api;

    protected function getActions()
    {
        return [
            'ping' => [self::class, 'ping'],
        ];
    }

    protected function authenticate()
    {
        return true;
    }

    protected function getRequestAction()
    {
        return \PayBreak\Rpc\Request::getParam('action');
    }

    protected function getRequestParams()
    {
        return (array) \PayBreak\Rpc\Request::getParam('params');
    }
    
    protected function ping(array $params)
    {
        return ['pong' => true, 'request' => $params];
    }
}

$obj = new MyApi();

$obj->executeCall();

```

Basic [demo](demo)

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email dev@paybreak.com instead of using the issue tracker.

## Credits

- [PayBreak][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/paybreak/rpc.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/PayBreak/rpc/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/paybreak/rpc.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/paybreak/rpc.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/paybreak/rpc.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/paybreak/rpc
[link-travis]: https://travis-ci.org/PayBreak/rpc
[link-scrutinizer]: https://scrutinizer-ci.com/g/PayBreak/rpc/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/PayBreak/rpc
[link-downloads]: https://packagist.org/packages/paybreak/rpc
[link-author]: https://github.com/PayBreak
[link-contributors]: ../../contributors
