<?php require_once 'init.php' ?>

<head>
    <title>Zgram</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<div id="app">
    <div v-if="current!=null">

        <?php include 'includes/nav.php' ?>;

        <div class="container">

            <div class=" left-container col-md-4">
                <br>
                <?php include 'includes/peers.php'; ?>
            </div>

            <div class=" right-container col-md-8">

                <div v-if="profile!=null">
                    <div class="title panel panel-heading ">
                        <?php include 'includes/chat_heading.php'; ?>
                    </div>

                    <?php include 'includes/messages.php'; ?>

                    <div v-if="(profile.type)=='group'">
                        <?php include 'includes/members_list.php'; ?>
                    </div>

                    <div class="compose">
                        <?php include 'includes/compose.php'; ?>
                    </div>
                </div>

                <div v-else="">
                    <div class="alert alert-info">Please select a chat!</div>
                </div>
            </div>
        </div>

        <?php include 'includes/profile_modal.php'; ?>
        <?php include 'includes/search_user_modal.php'; ?>
        <?php include 'includes/search_message_modal.php'; ?>
        <?php include 'includes/create_channel_modal.php'; ?>
        <?php include 'includes/create_group_modal.php'; ?>
        <?php include 'includes/load_profile_modal.php'; ?>


    </div>
</div>


<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/vue.js"></script>
<script src="js/app.js"></script>

<script>
    app.current =<?php echo json_encode(User::current()->toArray()) ?>;
    if (!app.current.name || app.current.name == "") {
        console.log("First login");
        $(window).load(function () {
            $('#profileModal').modal();
        })
    }
</script>