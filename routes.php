<?php
// Require router.php


// If you are using this in the root directory
// require_once("{$_SERVER['DOCUMENT_ROOT']}/router.php");

// If you are using this in a subdirectory
require_once("router.php");

// Disable error reporting
// error_reporting(0);
// ini_set('display_errors', 0);

// Route
get('/', 'pages/index.php');
get('/menu', 'pages/menu.php');
get('/register', 'pages/register.php');
get('/login', 'pages/login.php');
get('/menu1', 'pages/menu1.php');
get('/menu2', 'pages/menu2.php');
get('/dashboard', 'pages/dashboard.php');
get('/dashboard_m', 'pages/dashboard_m.php');
get('/order', 'pages/order.php');
get('/food', 'pages/food.php');
get('/report', 'pages/report.php');
get('/profile', 'pages/profile.php');
get('/manage', 'pages/manage.php');
get('/setting', 'pages/setting.php');
get('/order_history', 'pages/order_history.php');
get('/cart', 'pages/cart.php');
get('/payment', 'pages/payment.php');
get('/expenses', 'pages/expenses.php');

// get('/app/css/nav-bar', 'css/nav-bar.css');
// get('/app/css/side-bar', 'css/side-bar.css');

get('/admin', 'pages/admin.php');
get('/admin/users', 'pages/admin-users.php');
get('/admin/users/$id', 'pages/admin-user.php');

post('/api/login', 'apis/login.php');
post('/api/register', 'apis/register.php');
get('/api/logout', 'apis/logout.php');
get('/api/manage', 'apis/manage.php');
post('/api/users/$id/delete', 'apis/delete.php');
get('/api/users/$id', 'apis/get_single.php');
post('/api/users/$id/edit', 'apis/edit_profile.php');

get('/api/shops', 'apis/shops/get-all.php');
get('/api/shops/$id', 'apis/shops/get-single.php');
post('/api/shops/create', 'apis/shops/create.php');
post('/api/shops/$id/edit', 'apis/shops/update.php');
post('/api/shops/$id/delete', 'apis/shops/delete.php');

get('/api/menus', 'apis/foods/get-all.php');
get('/api/menus/$id', 'apis/foods/get-single.php');
post('/api/menus/create', 'apis/foods/create.php');
post('/api/menus/$id/edit', 'apis/foods/update.php');
post('/api/menus/$id/delete', 'apis/foods/delete.php');

get('/api/orders', 'apis/orders/get-all.php');
get('/api/orders/$id', 'apis/orders/get-single.php');
post('/api/orders/create', 'apis/orders/create.php');
post('/api/orders/$id/edit', 'apis/orders/update.php');
post('/api/orders/$id/delete', 'apis/orders/delete.php');

get('/api/expenses', 'apis/expenses/get-all.php');
get('/api/expenses/$id', 'apis/expenses/get-single.php');
post('/api/expenses/create', 'apis/expenses/create.php');
post('/api/expenses/$id/edit', 'apis/expenses/update.php');
post('/api/expenses/$id/delete', 'apis/expenses/delete.php');

get('/api/revenue', 'apis/Totalprice/revenue.php');

// get('/member/login', 'page/AuthSignin.php');
// get('/member/register', 'page/AuthSignup.php');
// get('/member/home', 'page/home.php');
// get('/member/history', '/page/history.php');
// get('/member/changepassword', '/page/changepassword.php');
// get('/member/logout', 'system/logout.php');
// get('/admin/backend', 'backend/backend.php');
// get('/member/topup', '/page/topup.php');
// get('/admin/backend/managewebsite', 'backend/managewebsite.php');
// get('/admin/backend/manageproduct', 'backend/manageproduct.php');
// get('/admin/backend/managestock', 'backend/managestock.php');
// get('/admin/backend/truemoneywallet', 'backend/truemoneywallet.php');
// get('/admin/backend/managemember', 'backend/managemember.php');
// get('/app/css/mongkuyrai', 'app.css.php');
// get('/api/v1/getitems', 'system/get_json_item.php');
// get('/api/v1/getitems/$id', 'system/get_json_item_view.php');
// get('/api/v1/users/web_profile_info/$i', 'profile.php');

// post('/api/v1/deleteitem', 'system/del_item.php');
// post('/api/v1/deletestock', 'system/del_stock.php');
// post('/api/v1/deletemember', 'system/del_member.php');
// post('/api/v1/edititem', 'system/edit_item.php');
// post('/api/v1/editstock', 'system/edit_stock.php');

// post('/api/v1/editmember', 'system/edit_member.php');
// post('/api/v1/member_point', 'system/member_point.php');
// post('/api/v1/addproduct', 'system/cargo_add.php');
// post('/api/v1/addstock', 'system/add_stock.php');
// post('/api/v1/changediscord', 'system/change_discord.php');
// post('/api/v1/changefacebook', 'system/change_facebook.php');
// post('/api/v1/changewallet', 'system/change_wallet.php');
// post('/api/v1/changeicon', 'system/change_icon.php');
// post('/api/v1/changelogo', 'system/change_logo.php');
// post('/api/v1/changecolor', 'system/change_color.php');
// post('/api/v1/bannerchange', 'system/change_banner.php');
// post('/api/v1/webhookchange', 'system/change_webhook.php');
// post('/api/v1/changenamestore', 'system/change_namestore.php');
// post('/api/v1/buy/$id', 'system/buyitem.php');
// post('/api/v1/login' , 'system/login.php');
// post('/api/v1/register' , 'system/register.php');
// post('/api/v1/topup/wallet', 'system/topup_wallet.php');
// post('/api/v1/changepassword', 'system/changepassword.php');
// post('/api/v1/logout', 'system/logout.php');

any('/404', 'pages/404.php');