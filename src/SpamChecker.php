<?php

namespace App;

use App\Entity\Comment;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SpamChecker
{
    private string $endpoint;

    public function __construct(
        private HttpClientInterface $client,
        string $akismetKey
    ) {
        $this->endpoint = sprintf(
            'https://%s.rest.akismet.com/1.1/comment-check',
            $akismetKey
        );
    }

    public function getSpamScore(Comment $comment, array $context): int
    {
        $response = $this->client->request(
            'POST',
            $this->endpoint,
            ['body' => $this->createRequestBody($comment)]
        );

        return $this->validateResponse($response);
    }

    private function createRequestBody(Comment $comment): array
    {
        return [
            'blog' => 'https://guestbook.example.com',
            'comment_type' => 'comment',
            'comment_author' => $comment->getAuthor(),
            'comment_author_email' => $comment->getEmail(),
            'comment_content' => $comment->getText(),
            'comment_date_gmt' => $comment->getCreatedAt()->format('c'),
            'blog_lang' => 'en',
            'blog_charset' => 'UTF-8',
            'is_test' => true
        ];
    }

    private function validateResponse(ResponseInterface $response): int
    {
        $headers = $response->getHeaders();
        $tip = $headers['x-akismet-pro-tip'][0] ?? '';
        if ($tip === 'discard') {
            return 2;
        }


        if (isset($headers['x-akismet-debug-help'][0])) {
            $message = sprintf(
                'Unable to check for spam: %s (%s).',
                $response->getContent(),
                $headers['x-akismet-debug-help'][0]
            );

            throw new \RuntimeException($message);
        }

        return $response->getContent() === 'true' ? 1 : 0;
    }
}