<style type="text/css">
    .vertical-timeline {
        width: 100%;
        position: relative;
        padding: 1.5rem 0 1rem;
    }

    .vertical-timeline::before {
        content: '';
        position: absolute;
        top: 0;
        left: 67px;
        height: 100%;
        width: 4px;
        background: #e9ecef;
        border-radius: .25rem;
    }

    .vertical-timeline-element {
        position: relative;
        margin: 0 0 1rem;
    }

    .vertical-timeline--animate .vertical-timeline-element-icon.bounce-in {
        visibility: visible;
        animation: cd-bounce-1 .8s;
    }

    .vertical-timeline-element-icon {
        position: absolute;
        top: 0;
        left: 60px;
    }

    .vertical-timeline-element-icon .badge-dot-xl {
        box-shadow: 0 0 0 5px #fff;
    }

    .badge-dot-xl {
        width: 18px;
        height: 18px;
        position: relative;
    }

    .badge:empty {
        display: none;
    }


    .badge-dot-xl::before {
        content: '';
        width: 10px;
        height: 10px;
        border-radius: .25rem;
        position: absolute;
        left: 50%;
        top: 50%;
        margin: -5px 0 0 -5px;
        background: #fff;
    }

    .vertical-timeline-element-content {
        position: relative;
        margin-left: 90px;
        font-size: .8rem;
    }

    .vertical-timeline-element-content .timeline-title {
        font-size: .8rem;
        text-transform: uppercase;
        margin: 0 0 .5rem;
        padding: 2px 0 0;
        font-weight: bold;
    }

    .vertical-timeline-element-content .vertical-timeline-element-date {
        display: block;
        position: absolute;
        left: -90px;
        top: 0;
        padding-right: 10px;
        text-align: right;
        color: #adb5bd;
        font-size: .7619rem;
        white-space: nowrap;
    }

    .vertical-timeline-element-content:after {
        content: "";
        display: table;
        clear: both;
    }
</style>
<div class="row d-flex justify-content-center mt-70 mb-70">

    <div class="col-md-12">


        <div class="main-card mb-3 card">
            <div class="card-body">
                <?php
                if (isset($timeline_data)) {
                    foreach ($timeline_data as $timeline) {
                ?>
                        <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                            <div class="vertical-timeline-item vertical-timeline-element">
                                <div>
                                    <span class="vertical-timeline-element-icon bounce-in">
                                        <i class="badge badge-dot badge-dot-xl badge-success"></i>
                                    </span>
                                    <div class="vertical-timeline-element-content bounce-in">
                                        <?php if ($timeline->title) {
                                            echo "<h4 class='timeline-title'>" . $timeline->title . "</h4>";
                                        } ?>
                                        <?php if ($timeline->caption) {
                                            echo "<p>" . $timeline->caption . "</p>";
                                        } ?>
                                        <?php if ($timeline->created_at) {
                                            $_time = strtotime($timeline->created_at);
                                            $day = date('l', $_time);
                                            $date = date('d/m/y', $_time);
                                            $time = date('h:i A', $_time);
                                            echo "<span class='vertical-timeline-element-date'>" . $day . '<br />' . $date . '<br />' . $time . "</span>";
                                        } ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>

    </div>
</div>