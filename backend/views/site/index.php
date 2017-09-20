<!-- Main content -->
<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work
        as expected...
    </noscript>

    <hgroup id="main-title" class="thin">
        <h1>Dashboard</h1>
        <h2>nov <strong>10</strong></h2>
    </hgroup>

    <div class="dashboard">

        <div class="columns">

            <div class="nine-columns twelve-columns-mobile" id="demo-chart">
                <!-- This div will hold the chart generated in the footer -->
            </div>

            <div class="three-columns twelve-columns-mobile new-row-mobile">
                <ul class="stats split-on-mobile">
                    <li><a href="#">
                            <strong>21</strong> new <br>accounts
                        </a></li>
                    <li><a href="#">
                            <strong>15</strong> referred new <br>accounts
                        </a></li>
                    <li>
                        <strong>5</strong> new <br>items
                    </li>
                    <li>
                        <strong>235</strong> new <br>comments
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="with-padding">

        <div class="columns">

            <div class="four-columns six-columns-tablet twelve-columns-mobile">

                <h2 class="relative thin">
                    新用户
                    <span class="info-spot">
							<span class="icon-info-round"></span>
							<span class="info-bubble">
								这是一个帮助用户的信息.
							</span>
						</span>
                    <span class="button-group absolute-right">
							<a href="javascript:openModal()" title="Add user" class="button icon-plus-round">添加</a>
							<a href="javascript:openLoadingModal()" title="Reload list" class="button icon-redo">刷新</a>
						</span>
                </h2>

                <ul class="list spaced">
                    <?php

                    foreach ($data as $item) {
                        ?>
                        <li>
                            <a href="#" class="list-link icon-user" title="点击编辑">
                                <span class="meter orange-gradient"></span>
                                <span class="meter orange-gradient"></span>

<!--                                --><?php
//                                for ($i = 0; $i < rand(0, 3); $i++) {
//                                    ?>
<!--                                    <span class="meter"></span>-->
<!--                                    --><?php
//                                }
//                                ?>
                                <strong>&nbsp;&nbsp;</strong> <?= $item['userName'] ?>
                            </a>
                            <div class="button-group absolute-right compact show-on-parent-hover">
                                <a href="#" class="button icon-pencil">编辑</a>
                                <a href="#" class="button icon-gear with-toolti" title="Other actions"></a>
                                <a href="#" class="button icon-trash with-tooltip confirm" title="Delete"></a>
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                </ul>

            </div>

            <div class="new-row-mobile four-columns six-columns-tablet twelve-columns-mobile">
                <div class="button-height wrapped align-right">
                    <span class="float-left mid-margin-left">Want some modals?</span>
                    <span class="button-group">
							<a href="javascript:openAlert();" class="button">Alert</a>
							<a href="javascript:openPrompt();" class="button">Prompt</a>
							<a href="javascript:openConfirm();" class="button">Confirm</a>
						</span>
                </div>

            </div>
        </div>

    </div>

</section>
<!-- End main content -->