<?php

use App\Photo;
use App\Repositories\PhotoRepository;
use App\Repositories\Proxies\PhotoRepositoryProxy;
use App\Services\RateLimiter;

/**
 * This test case is not exhaustive by any means. It is simply here
 * to ensure that the rate limiter does indeed work and therefore is
 * a demonstration of the proxy pattern working.
 */
class PhotoRepositoryProxyTest extends TestCase
{
    /**
     * Test that multiple calls to the PhotoRepository are intercepted
     * by the proxy and rate limited. Once banned the calls should
     * throw an exception.
     *
     * @expectedException \App\Exceptions\RateLimitException
     * @expectedExceptionCode 429
     * @expectedExceptionMessage App\Repositories\PhotoRepository::create() cannot be called more than 10 in 10 minutes.
     */
    public function testMultipleCallsToPhotoRepositoryAreRateLimited()
    {
        // Sample attributes
        $attributes = [
            'caption' => 'Test Image',
            'path' => '/tmp/path.jpg',
            'size' => 1024,
            'type' => 'image/jpeg',
        ];

        // Mock Photo model so we do not hit the DB.
        $photo = $this->getMockBuilder(Photo::class)
            ->setMethods(['fill', 'save', 'newInstance'])
            ->getMock();
        $photo->method('save')
            ->willReturn(true);
        $photo->method('fill')
            ->with($attributes)
            ->willReturn(true);
        $photo->method('newInstance')
            ->willReturn($photo);

        // Construct the PhotoRepository manually so we can inject
        // the mocked Photo model.
        $photoProxy = new PhotoRepositoryProxy(new PhotoRepository($photo), app(RateLimiter::class));

        // Continue creating Photos until we hit the limit.
        // We need to hit it one more than the limit to get the exception.
        for ($x = 0; $x < 11; ++$x) {
            // You could just as easily hit the API or controller to
            // see that the repository is still indeed called in the
            // end and the proxy properly rate limits it.
            $model = $photoProxy->create($attributes);
            $this->assertInstanceOf(Photo::class, $model);
        }
    }
}
