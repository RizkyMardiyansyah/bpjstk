<style>
    .bggold{
        background-color: #d4af37;
    }
    .bgsilver{
        background-color: #c7d1da;
    }
    .bgbronze{
        background-color: #88540b;
    }
    .notification{
        border-bottom: 1px solid rgba(0, 0, 0, 0.6);
        margin-bottom: 20px;
        
    }
    .border{
        border: 1px solid rgba(0, 0, 0, 0.4);
        border-radius: 10px;
        padding: 10px;
        margin: 5px;
    }
    .filter .btn-primary {
    font-size: 10px !important;
    width: 80px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.5;
    border-radius: 4px;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
}

.btn{
    height: 30px;
}


.btnn {
    font-size: 10px !important;
    width: 80px;
    color: var(--primary);
    
    background-color: transparent;
    border: 1px solid var(--primary);
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.5;
    border-radius: 4px;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
    
}

.btnn:hover {
    color: #fff;
    background-color:var(--primary);
    border-color:var(--primary);
    .btn-primary{
    color: var(--primary);
    background-color: transparent;}
}

.bttn:focus,
.btnn.focus {
    color: #fff;
    background-color: var(--primary);
    border-color: var(--primary);
}

</style>
<div class="row m-0" style="margin-top: -20px">
    <div class="row m-0">       
        <div class="row m-0 justify-content-between align-items-center">
                <h3 class="text-dark-blue mb-2" >{{__('update.leaderboard')}}</h3>
        </div> 
    </div>
    <div class=" filter row m-0 mb-1 ml-auto">
        <button id="mostPointsTeachersButton" style="border-radius:0; border-top-left-radius: 10px; border-bottom-left-radius: 10px;" class="ml-auto teacher btn btn-primary">{{__("public.colaburator")}}</button>
        <button id="mostPointsUsersButton" style="border-radius:0; border-top-right-radius: 10px; border-bottom-right-radius: 10px;" class="student btn btnn">{{__("public.students")}}</button>
    </div>
</div>
    
        <div class="bg-white noticeboard rounded-sm panel-shadow py-10 py-md-20 px-15 px-md-30 height">
            

            
        
           <div id="mostPointsUsers" hidden>
            @foreach($mostPointsUsers as $mostPoint)
                
            <div class="rounded-sm border p-10 d-flex align-items-center {{ ($loop->iteration > 1) ? 'mt-10' : '' }}">
                <div class="leaderboard-others-avatar">
                    <img src="{{ $mostPoint->user->getAvatar() }}" class="img-cover rounded-circle" alt="{{ $mostPoint->user->full_name }}">
                </div>

                <div class="flex-grow-1 ml-15">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-8 col-md-9">
                            <span class="font-14 font-weight-bold text-secondary d-block">{{ $mostPoint->user->full_name }}</span>
                            <span class="text-gray font-12 font-weight-500">{{ $mostPoint->total_points }} {{ trans('update.points') }}</span>
                        </div>
                        @if(($loop->iteration)==1)
                        <div style="height: 100%; border-top-left-radius: 50px; border-bottom-left-radius: 50px;" class="col-3 col-md-2  ml-auto bggold justify-content-between align-items-center mr-1 pr-2 pl-3 pt-1 pb-1">
                            <span class="font-14 font-weight-bold text-white d-block text-center">Top</span>
                            <span style="font-size: 20px" class=" font-weight-bold text-white d-block text-center">{{ ($loop->iteration )}}</span>
                        </div>
                        @elseif(($loop->iteration)==2)
                        <div style="height: 100%; border-top-left-radius: 50px; border-bottom-left-radius: 50px;" class="col-3 col-md-2 ml-auto bgbronze justify-content-between align-items-center mr-1 pr-2 pl-3 pt-1 pb-1">
                            <span class="font-14 font-weight-bold text-white d-block text-center">Top</span>
                            <span style="font-size: 20px" class=" font-weight-bold text-white d-block text-center">{{ ($loop->iteration )}}</span>
                        </div>
                        @else
                        <div style="height: 100%; border-top-left-radius: 50px; border-bottom-left-radius: 50px;" class="col-3 col-md-2 ml-auto bgsilver justify-content-between align-items-center mr-1 pr-2 pl-3 pt-1 pb-1">
                            <span class="font-14 font-weight-bold text-secondary d-block text-center">Top</span>
                            <span style="font-size: 20px" class=" font-weight-bold text-secondary d-block text-center">{{ ($loop->iteration )}}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
                
            @endforeach
        </div>

            <div id="mostPointsTeachers">
            @foreach($mostPointsTeachers as $mostPoint)
                
            <div class="rounded-sm border p-10 d-flex align-items-center {{ ($loop->iteration > 1) ? 'mt-10' : '' }}">
                <div class="leaderboard-others-avatar">
                    <img src="{{ $mostPoint->user->getAvatar() }}" class="img-cover rounded-circle" alt="{{ $mostPoint->user->full_name }}">
                </div>

                <div class="flex-grow-1 ml-15">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-8 col-md-9">
                            <span class="font-14 font-weight-bold text-secondary d-block">{{ $mostPoint->user->full_name }}</span>
                            <span class="text-gray font-12 font-weight-500">{{ $mostPoint->total_points }} {{ trans('update.points') }}</span>
                        </div>
                        @if(($loop->iteration)==1)
                        <div style="height: 100%; border-top-left-radius: 50px; border-bottom-left-radius: 50px;" class="col-3 col-md-2 ml-auto bggold justify-content-between align-items-center mr-1 pr-2 pl-3 pt-1 pb-1">
                            <span class="font-14 font-weight-bold text-white d-block text-center">Top</span>
                            <span style="font-size: 20px" class=" font-weight-bold text-white d-block text-center">{{ ($loop->iteration )}}</span>
                        </div>
                        @elseif(($loop->iteration)==2)
                        <div style="height: 100%; border-top-left-radius: 50px; border-bottom-left-radius: 50px;" class="col-3 col-md-2 ml-auto bgbronze justify-content-between align-items-center mr-1 pr-2 pl-3 pt-1 pb-1">
                            <span class="font-14 font-weight-bold text-white d-block text-center">Top</span>
                            <span style="font-size: 20px" class=" font-weight-bold text-white d-block text-center">{{ ($loop->iteration )}}</span>
                        </div>
                        @else
                        <div style="height: 100%; border-top-left-radius: 50px; border-bottom-left-radius: 50px;" class="col-3 col-md-2 ml-auto bgsilver justify-content-between align-items-center mr-1 pr-2 pl-3 pt-1 pb-1">
                            <span class="font-14 font-weight-bold text-secondary d-block text-center">Top</span>
                            <span style="font-size: 20px" class=" font-weight-bold text-secondary d-block text-center">{{ ($loop->iteration )}}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
                
            @endforeach
        </div>
        <script>
        const teachersButton = document.getElementById('mostPointsTeachersButton');
        const usersButton = document.getElementById('mostPointsUsersButton');
        const teachersDiv = document.getElementById('mostPointsTeachers');
        const usersDiv = document.getElementById('mostPointsUsers');

  teachersButton.addEventListener('click', function() {
    teachersDiv.removeAttribute('hidden');
    usersDiv.setAttribute('hidden', 'true');

    teachersButton.classList.add('btn-primary');
    teachersButton.classList.remove('btnn');
    usersButton.classList.add('btnn');
    usersButton.classList.remove('btn-primary');

  });

  usersButton.addEventListener('click', function() {
    teachersDiv.setAttribute('hidden', 'true');
    usersDiv.removeAttribute('hidden');


    usersButton.classList.add('btn-primary');
    usersButton.classList.remove('btnn');
    teachersButton.classList.add('btnn');
    teachersButton.classList.remove('btn-primary');

  });
        </script>
        </div>
    
        </div>
        
    
    
    


