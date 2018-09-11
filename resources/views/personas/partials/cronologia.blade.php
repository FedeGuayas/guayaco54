<div class="tab-pane fade show active" id="timeline" role="tabpanel">
    <div class="pd-20">
        <div class="profile-timeline">

            {{--//foreach por inscripciones--}}
            <div class="timeline-month">
                <h5>{{ucwords(\Carbon\Carbon::now()->formatLocalized('%B, %Y'))}}</h5>
            </div>
            <div class="profile-timeline-list">
                <ul>
                    <li>
                        <div class="date">12 Agosto</div>
                        <div class="task-name"><i class="ion-android-alarm-clock"></i>5K
                        </div>
                        <p>Precio categoria kms.</p>
                        {{--<div class="task-time">07:30 am</div>--}}
                    </li>
                </ul>
            </div>
            {{--end foreach--}}

            <div class="timeline-month">
                <h5>Octubre, 2017</h5>
            </div>
            <div class="profile-timeline-list">
                <ul>
                    <li>
                        <div class="date">2 Octubre</div>
                        <div class="task-name"><i class="ion-android-alarm-clock"></i> 1KM
                        </div>
                        <p>Precio categoria kms</p>
                        {{--<div class="task-time">09:30 am</div>--}}
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>