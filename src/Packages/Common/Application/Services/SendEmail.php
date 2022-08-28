<?php

declare(strict_types=1);

namespace App\Packages\Common\Application\Services;

use App\Packages\Club\Domain\Entity\Club;
use App\Packages\Coach\Domain\Entity\Coach;
use App\Packages\Player\Domain\Entity\Player;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class SendEmail
{
    private const CHANEL = ['email'];
    private const SUBJECT = '%s';
    private const CONTENT = '%s has been %s to %s';

    public function __construct(
        private NotifierInterface $notifier
    )
    {
    }

    public function __invoke(Player|Coach $employee, string $type, Club $club = null): void
    {
        $notification = (new Notification($this->getSubject($employee, $type), self::CHANEL))
            ->content($this->getContent($employee, $type, $club));

        $recipient = new Recipient(
            $employee->getEmail()->value()
        );

        $this->notifier->send($notification, $recipient);
    }

    private function getSubject(Player|Coach $employee, string $type): string
    {
        switch ($type) {
            case 'add':
                return sprintf(
                    self::SUBJECT,
                    $employee instanceof Player ? 'Add Player' : 'Add Coach'
                );

            case 'delete':
                return sprintf(
                    self::SUBJECT,
                    $employee instanceof Player ? 'Deleted Player' : 'Deleted Coach'
                );

            default:
                return '';
        }
    }

    private function getContent(Player|Coach $employee, string $type, Club $club = null): string
    {
        switch ($type) {
            case 'add':
                return sprintf(
                    self::CONTENT,
                    $employee->getName()->value(),
                    'added',
                    $employee->getClub()->getName()->value()
                );

            case 'delete':
                return sprintf(
                    self::CONTENT,
                    $employee->getName()->name() . ' ' . $employee->getName()->surname(),
                    'deleted',
                    !is_null($club) ? $club->getName()->value() : $employee->getClub()->getName()->value()
                );

            default:
                return '';
        }
    }
}