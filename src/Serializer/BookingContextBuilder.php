<?php

namespace App\Serializer;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\Booking;

final class BookingContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $authorizationChecker;

    public function __construct(SerializerContextBuilderInterface $decorated, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $resourceClass = $context['resource_class'] ?? null;

        if ($resourceClass === Booking::class && $this->authorizationChecker->isGranted('ROLE_ADMIN') && ($request->getMethod() == "PUT")) {
            if($normalization === false) {
                $context['groups'][] = 'get_role_admin';
            }
        } else {
            $context['groups'][] = 'get_role_user';
        }
        return $context;
    }
}