<?php

/**
 * This file is part of prooph/micro-do.
 * (c) 2016-2018 prooph software GmbH <contact@prooph.de>
 * (c) 2016-2018 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\MicroDo\UserWrite\Model\Command;

use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use Prooph\MicroDo\UserWrite\Model\EmailAddress;
use Prooph\MicroDo\UserWrite\Model\UserId;
use Zend\Validator\EmailAddress as EmailAddressValidator;

final class RegisterUser extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public static function withData(string $userId, string $name, string $email): RegisterUser
    {
        return new self([
            'user_id' => (string) $userId,
            'name' => (string) $name,
            'email' => (string) $email,
        ]);
    }

    public function userId(): UserId
    {
        return UserId::fromString($this->payload['user_id']);
    }

    public function name(): string
    {
        return $this->payload['name'];
    }

    public function emailAddress(): EmailAddress
    {
        return EmailAddress::fromString($this->payload['email']);
    }

    protected function setPayload(array $payload): void
    {
        Assertion::keyExists($payload, 'user_id');
        Assertion::uuid($payload['user_id']);
        Assertion::keyExists($payload, 'name');
        Assertion::string($payload['name']);
        Assertion::keyExists($payload, 'email');
        $validator = new EmailAddressValidator();
        Assertion::true($validator->isValid($payload['email']));

        $this->payload = $payload;
    }
}
