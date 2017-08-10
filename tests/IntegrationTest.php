<?php

namespace Waavi\SaveUrl\Test;

class IntegrationTest extends TestCase
{
    /**
     *  @test
     */
    public function it_saves_the_url()
    {
        $response = $this->call('GET', '/save');
        $response->assertSessionHas('saved-url', 'http://localhost/save');
    }

    /**
     *  @test
     */
    public function it_keeps_url_parameters()
    {
        $response = $this->call('GET', '/save?param=value');
        $response->assertSessionHas('saved-url', 'http://localhost/save?param=value');
    }

    /**
     *  @test
     */
    public function it_does_not_save_marked_urls()
    {
        $this->call('GET', '/no-save');
        $this->assertFalse($this->app['session.store']->has('saved-url'));
    }

    /**
     *  @test
     */
    public function it_does_not_save_urls_if_logged_in()
    {
        $this->call('GET', '/login/1');
        $this->call('GET', '/save');
        $this->assertFalse($this->app['session.store']->has('saved-url'));
    }

    /**
     *  @test
     */
    public function it_does_not_save_post_requests()
    {
        $this->call('POST', '/save');
        $this->assertFalse($this->app['session.store']->has('saved-url'));
    }

    /**
     *  @test
     */
    public function it_does_not_save_ajax_requests()
    {
        $this->call('GET', '/save', [], [], [], ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $this->assertFalse($this->app['session.store']->has('saved-url'));
    }

    /**
     * @test
     */
    public function it_redirects_after_login()
    {
        $this->call('GET', '/save');
        $response = $this->call('GET', '/login/1');
        $response->assertRedirect('/save');
    }
}
