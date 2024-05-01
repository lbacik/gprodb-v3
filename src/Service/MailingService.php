<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MailingProvider;
use App\Entity\MailingRConfig;
use App\Repository\MailingProviderRepositoryInterface;
use App\Repository\MailingRConfigRepositoryInterface;
use App\Type\MailingProvider as MailingProviderEnum;
use App\Type\ProjectSettings;

class MailingService
{
    public function __construct(
        private readonly MailingProviderRepositoryInterface $mailingProviderRepository,
        private readonly MailingRConfigRepositoryInterface $mailingRConfigRepository,
    ) {
    }

    public function save(ProjectSettings $current, MailingProviderEnum $provider, mixed $config): void
    {
        $currentProvider = $current->getMailingProvider();

        if ($currentProvider === null) {
            $current->setMailingProvider(
                (new MailingProvider())
                    ->setNewsletter($provider)
                    ->setParent($current->getLandingPage()->getId())
            );
        } else {
            $currentProvider->setNewsletter($provider);
        }

        if ($provider === MailingProviderEnum::MAILINGR && $config instanceof MailingRConfig) {
            $currentConfig = $current->getMailingRConfig();
            if ($currentConfig === null) {
                $current->setMailingRConfig($config);
                $current
                    ->getMailingRConfig()
                    ->setParent(
                        $current->getLandingPage()->getId()
                    );
            } else {
                $currentConfig->setApiKey($config->getApiKey());
                $currentConfig->setProductId($config->getProductId());
            }
        }

        $this->mailingProviderRepository->save($current->getMailingProvider());
        if ($current->getMailingRConfig() !== null) {
            $this->mailingRConfigRepository->save($current->getMailingRConfig());
        }
    }
}
