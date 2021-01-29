<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_view_register_in_english()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->assertSee(trans('register.register'))
                ->assertSee(trans('register.employer'))
                ->assertSee(trans('register.candidate'))
                ->assertSee(trans('register.fullname'))
                ->assertSee(trans('register.email'))
                ->assertSee(trans('register.password'))
                ->assertSee(trans('register.confirm_password'))
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
                ->assertSee(trans('home.email'))
                ->assertSee(trans('home.subcribe'))
                ->assertSee(trans('home.english'));
        });
    }

    public function test_action_register_employer()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->click('#employer')
                ->assertSee(trans('register.register'))
                ->assertSee(trans('register.employer'))
                ->assertSee(trans('register.candidate'))
                ->assertSee(trans('register.fullname'))
                ->assertSee(trans('register.email'))
                ->assertSee(trans('register.password'))
                ->assertSee(trans('register.confirm_password'))
                ->assertSee(trans('register.address'))
                ->assertSee(trans('register.name_company'))
                ->assertSee(trans('register.link_website'))
                ->assertSee(trans('register.introduce_company'))
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
                ->assertSee(trans('home.email'))
                ->assertSee(trans('home.subcribe'))
                ->assertSee(trans('home.english'));
        });
    }

    public function test_action_listjobs()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->clickLink(trans('home.listjobs'))
                ->assertPathIs('/project1/public/jobs');
        });
    }

    public function test_action_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->clickLink(trans('home.login'))
                ->assertPathIs('/project1/public/login');
        });
    }

    public function test_action_register()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->clickLink(trans('register.register'))
                ->assertPathIs('/project1/public/register');
        });
    }

    public function test_submit_button_register()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->press('form #candidate-button')
                ->assertPathIs('/project1/public/register');
        });
    }

    public function test_view_register_in_vietnamese()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->clickLink(trans('home.english'))
                ->clickLink(trans('home.vietnamese'))
                ->assertSee('Đăng kí')
                ->assertSee('Tôi muốn trở thành nhà tuyển dụng')
                ->assertSee('Tôi muốn trở thành người ứng tuyển')
                ->assertSee('Họ và tên')
                ->assertSee('Email')
                ->assertSee('Mật khẩu')
                ->assertSee('Nhập lại mặt khẩu')
                ->assertSee('Danh sách công việc')
                ->assertSee('Đăng nhập')
                ->assertSee('Seeking')
                ->assertSee('Nhà tuyển dụng')
                ->assertSee('Đăng tuyển')
                ->assertSee('Cách thức hoạt động')
                ->assertSee('Tìm kiếm công việc')
                ->assertSee('Đăng ký email')
                ->assertSee('Ứng viên')
                ->assertSee('Tiếng Việt');
        });
    }
}
