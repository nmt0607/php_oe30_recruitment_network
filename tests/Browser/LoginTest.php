<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class CreateJobTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_view_login_in_english()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee(trans('login.newjob'))
                ->assertSee(trans('login.login-title'))
                ->assertSee(trans('login.rememberme'))
                ->assertSee(trans('login.login'))
                ->assertSee(trans('login.social-title'))
                ->assertSee(trans('login.twitter'))
                ->assertSee(trans('login.facebook'))
                ->assertSee(trans('login.google'))
                ->assertSee(trans('login.register'))
                ->assertSee(trans('home.title'))
                ->assertSee(trans('home.listjobs'))
                ->assertSee(trans('home.login'))
                ->assertSee(trans('home.register'))
                ->assertSee(trans('home.seeking'))
                ->assertSee(trans('home.employer'))
                ->assertSee(trans('home.post_job'))
                ->assertSee(trans('home.how_it_work'))
                ->assertSee(trans('home.find_job'))
                ->assertSee(trans('home.candidate'))
                ->assertSee(trans('home.signup_email'))
                ->assertSee(trans('home.subcribe'))
                ->assertSee(trans('home.english'));
        });
    }

    public function test_click_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->clickLink(trans('home.login'))
                ->assertPathIs('/project1/public/login');
        });
    }

    public function test_action_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'adsada')
                ->type('password', '12345678')
                ->press('form .login-btn')
                ->assertPathIs('/project1/public/login');
        });
    }

    public function test_click_register()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->clickLink(trans('register.register'))
                ->assertPathIs('/project1/public/register');
        });
    }

    public function test_click_find_job()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->clickLink(trans('home.find_job'))
                ->assertPathIs('/project1/public/jobs');
        });
    }

    public function test_click_post_job()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->clickLink(trans('home.post_job'))
                ->assertPathIs('/project1/public/jobs/create');
        });
    }

    public function test_view_login_in_vietnamese()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->clickLink(trans('home.english'))
                ->clickLink(trans('home.vietnamese'))
                ->assertSee('Công việc mới đăng')
                ->assertSee('Đăng nhập vào tài khoản của bạn')
                ->assertSee('Ghi nhớ đăng nhập')
                ->assertSee('Đăng nhập')
                ->assertSee('Đăng nhập bằng')
                ->assertSee('Twitter')
                ->assertSee('Facebook')
                ->assertSee('Google')
                ->assertSee('Đăng ký ngay')
                ->assertSee('Danh sách công việc')
                ->assertSee('Đăng nhập')
                ->assertSee('Seeking')
                ->assertSee('Nhà tuyển dụng')
                ->assertSee('Đăng tuyển')
                ->assertSee('Cách thức hoạt động')
                ->assertSee('Tìm kiếm công việc')
                ->assertSee('Ứng viên')
                ->assertSee('Đăng ký email')
                ->assertSee('Đăng ký')
                ->assertSee('Tiếng Việt');
        });
    }
}
