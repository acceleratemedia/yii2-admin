<?php

/* @var $this yii\web\View */

$this->toolbar = null;
$this->title = 'Backend';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your application.</p>

        <p>To change the default page from this one to one of your choosing, create a controller and configure the <code>defaultRoute</code> property of your application configuration. For example, if you would like to create a dashboard page with helpful links in your application you can do the following:</p>
        <p>Create <code>@app/controllers/DashboardController.php</code>:</p>

        <p>In <code>@app/config/main.php</code>:</p>
        <pre><code>
return [
    ...
    'defaultRoute' => 'dashboard/index',
    ...
];
        </code></pre>

        <p>In <code>@app/controllers/DashboardController.php</code>:</p>
        <pre><code>
<?= htmlentities('<?php'); ?>

namespace backend\controllers;

use yii\web\Controller;

class DashboardController extends Controller
{
    /**
     * Display helpful links for navigating the application
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
        </code></pre>

        <p>In <code>@app/views/dashboard/index.php</code>:</p>
        <pre><code>
<?= htmlentities('<?php'); ?>

/* @var $this \yii\web\View */

$this->title = Yii::$app->name;
?>
<p>Now you can enter your own content here</p>
        </code></pre>
    </div>
</div>
<style>
pre{
background-color:#fff;
padding-left:10px;
}
</style>