<div class="px-4">
    <div class="px-4" style="position: relative;">
    @if ($message)
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="max-width: 500px; margin: auto;">
                {{ $message }}
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"
                        style="font-size: 0.75rem; padding: 0.2rem 0.4rem; margin-top: 4px;"></button>
                </div>
                @endif

        <div class="col-md-12  mt-3" style="height:60px;margin-top:10px">

<div class="row bg-white rounded border d-flex" style="height:80px; ">
    <div  style="display:flex; flex-direction:row;">


        <div class=" mt-3" style="height:60px">
            @if(auth('emp')->check() || auth('hr')->check())
            @php
            // Determine the employee ID based on the authentication guard
            $empEmployeeId = auth('emp')->check() ? auth('emp')->user()->emp_id : auth('hr')->user()->hr_emp_id;

            // Fetch the employee details from EmployeeDetails model
            $employeeDetails = \App\Models\EmployeeDetails::where('emp_id', $empEmployeeId)->first();
            @endphp

            @if(($employeeDetails->image) && $employeeDetails->image !== 'null')
            <img style="border-radius: 50%; " height="50" width="50" src="{{ $employeeDetails->image_url }}" alt="Employee Image">
            @else
            @if($employeeDetails && $employeeDetails->gender == "Male")
            <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
            @elseif($employeeDetails && $employeeDetails->gender == "Female")
            <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
            @else
            <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/user.jpg")}}" alt="Default Image">
            @endif
            @endif
            @else
            <p>User is not authenticated.</p>
            @endif
        </div>
        <div   style="color:#3b4452; display:flex;flex-direction:column;justify-content:center;margin-left:10px">
            @if(auth()->guard('emp')->check())
            <span class="text-base">Hey {{ ucwords(strtolower(auth()->guard('emp')->user()->first_name)) }} {{ ucwords(strtolower(auth()->guard('emp')->user()->last_name)) }}</span>
            @elseif(auth()->guard('hr')->check())
            <span class="text-base">Hey {{ ucwords(strtolower(auth()->guard('hr')->user()->employee_name)) }}</span>
            @else
            <p>No employee details available.</p>
            @endif

            <div class="text-xs" style="color:#3b4452;">Ready to dive in?</div>
        </div>
        <div style="margin-left:auto; display:flex;align-items:center">
        <button wire:click="addFeeds" class="flex flex-col justify-between items-start group w-20  p-1 rounded-md border border-purple-200" style="background-color: #F1ECFC;border:1px solid purple;border-radius:5px;width:130px">
                <div class="w-6 h-6 rounded bg-purple-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file stroke-current group-hover:text-purple-600 stroke-1 text-purple-400">
                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                        <polyline points="13 2 13 9 20 9"></polyline>
                    </svg>
                </div>
                <div class="row  mt-1">
                    <div class="text-left text-xs" style="margin-left:5px" wire:click="addFeeds">Create Posts</div>

                </div>
            </button>
        </div>
    </div>
    <div class=" mt-2 bg-white d-flex align-items-center ">

        <div style="display:flex;margin-left:auto">
           

            @if($showFeedsDialog)
            <div class="modal" tabindex="-1" role="dialog" style="display: block; color: #3b4452; font-family: Montserrat, sans-serif;">
                <div class="modal-dialog modal-dialog-centered" role="document" style="color: #3b4452;">
                    <div class="modal-content" style="border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <div class="modal-header" style="border-bottom: 1px solid #ccc; padding: 15px; height: 40px; margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                            <!-- <h5 class="modal-title" style="font-weight: 500; font-size: 1.25rem; color: #3b4452;">Creating a Post</h5> -->
                            <p style="font-weight:600px">Create a post</p>
                            <span style="margin-left: auto;margin-top:-5px">
                                <img src="{{ asset('images/Posts.jpg') }}" style="height: 30px; border-radius: 5px;">
                            </span>
                        </div>


                        @if(Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center justify-content-center" role="alert"
                            style="font-size: 0.875rem; width: 90%; margin: 10px auto; padding: 10px; border-radius:4px; background-color: #f8d7da; color: #721c24;">
                            {{ Session::get('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="margin-left: 10px;margin-top:-5px"></button>
                        </div>
                        @endif
                        <form wire:submit.prevent="submit" enctype="multipart/form-data">
    <div class="modal-body" style="padding: 20px;">
        <!-- Category Selection -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="category" style="font-weight: 600; color: #3b4452;">You are posting in:</label>
            <select wire:model.lazy="category" class="form-select" id="category" style="border: 1px solid #ccc; border-radius: 4px; padding: 5px; font-size: 0.75rem; color: #3b4452; margin-top: 5px; height: 30px;">
                <option value="">Select Category</option>
                <option value="Appreciations">Appreciations</option>
                <option value="Buy/Sell/Rent">Buy/Sell/Rent</option>
                <option value="Companynews">Company News</option>
                <option value="Events">Events</option>
                <option value="Everyone">Everyone</option>
                <option value="Hyderabad">Hyderabad</option>
                <option value="US">US</option>
            </select>
            @error('category') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- Description Input -->
        <div class="form-group">
            <label for="content" style="font-weight: 600; color: #3b4452;">Write something here:</label>
            <textarea wire:model.lazy="description" class="form-control" id="content" rows="2" style="border: 1px solid #ccc; border-radius: 4px; padding: 10px; font-size: 0.875rem; resize: vertical; width: 100%; margin-left: -250px; margin-top: 5px" placeholder="Enter your description here..."></textarea>
            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
       <!-- File Input -->
       <div id="flash-message-container" style="display: none;margin-top:10px" class="alert alert-success"
                                    role="alert"></div>
        <!-- File Upload -->
        <div class="form-group" style="margin-top: 5px;">
            <label for="file_path" style="font-weight: 600; color: #3b4452;">Upload Attachment:</label>
            <div style="text-align: start;">
           

                <input type="file" wire:model="file_path" class="form-control"  id="file_path"  style="margin-top:5px" onchange="handleImageChange()">
                @error('file_path') <span class="text-danger">{{ $message }}</span> @enderror

                <!-- Success Message -->
           
        
            </div>
        </div>
    </div>

    <!-- Submit & Cancel Buttons -->
    <div class="modal-footer" style="border-top: 1px solid #ccc;">
    <div class="d-flex justify-content-center" style="width: 100%;">
            <button type="submit" wire:target="file_path" wire:loading.attr="disabled" class="submit-btn">Submit</button>
            <button wire:click="closeFeeds" type="button" class="cancel-btn" style="border:1px solid rgb(2,17,79); margin-left: 10px">Cancel</button>
        </div>
    </div>
</form>


                    </div>
                </div>
            </div>




            <div class="modal-backdrop fade show"></div>
            @endif
        </div>
    </div>
</div>
<!-- Additional row -->
<div class="row mt-2 d-flex" >

    <div class="col-md-4 bg-white p-3" style="border-radius:5px;border:1px solid silver;height:fit-content">

        <p style="font-weight: 500;font-size:13px;color:#47515b;">Filters</p>
        <hr style="width: 100%;border-bottom: 1px solid grey;">


        <p style="font-weight: 500;font-size:13px;color:#47515b;cursor:pointer">Activities</p>
        <div class="activities" style="width: 100%; height: 30px;">
            <label class="custom-radio-label" style="display: flex; align-items: center; padding: 5px; height: 100%;">
                <input type="radio" name="radio" value="activities"  data-url="/Feeds" onclick="handleRadioChange(this)">
                <div class="feed-icon-container" style="margin-left: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file stroke-current text-purple-400 stroke-1" style="width: 1rem; height: 1rem;">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <rect x="7" y="7" width="3" height="9"></rect>
                        <rect x="14" y="7" width="3" height="5"></rect>
                    </svg>
                </div>
                <span class="custom-radio-button bg-blue" style="margin-left: 10px; font-size: 8px;"></span>
                <span style="color: #778899; font-size: 12px; font-weight: 500; margin-left: 5px;">All Activities</span>
            </label>
        </div>


        <div class="posts" style="width: 100%; height: 30px;">
            <label class="custom-radio-label" style="display: flex; align-items: center; padding: 5px; height: 100%;">
                @if(auth()->guard('emp')->check())
                <input type="radio" id="radio-emp" name="radio"  value="posts" data-url="/everyone" onclick="handleRadioChange(this)">
                @elseif(auth()->guard('hr')->check())
                <input type="radio" id="radio-hr" name="radio"  value="posts" data-url="/hreveryone" onclick="handleRadioChange(this)">
                @else
                <p>No employee details available.</p>
                @endif
                <div class="feed-icon-container" style="margin-left: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file stroke-current text-purple-400 stroke-1" style="width: 1rem; height: 1rem;">
                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                        <polyline points="13 2 13 9 20 9"></polyline>
                    </svg>
                </div>
                <span class="custom-radio-button bg-blue" style="margin-left: 10px; font-size: 10px;"></span>
                <span style="color: #778899; font-size: 12px; font-weight: 500; margin-left: 5px;">Posts</span>
            </label>
        </div>

        @if($isManager)
    <div class="post-requests" style="width: 100%; height: 30px;">
        <label class="custom-radio-label" style="display: flex; align-items: center; padding: 5px; height: 100%;">
            @if(auth()->guard('emp')->check())
                <input type="radio" id="radio-emp" name="radio" style="margin-left:5px" checked value="post-requests" data-url="/emp-post-requests" onclick="handleRadioChange(this)">
            @elseif(auth()->guard('hr')->check())
                <input type="radio" id="radio-hr" name="radio" style="margin-left:5px" checked value="post-requests" data-url="/hr-post-requests" onclick="handleRadioChange(this)">
            @endif
            <div class="feed-icon-container" style="margin-left: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file stroke-current text-purple-400 stroke-1" style="width: 1rem; height: 1rem;">
                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                    <polyline points="13 2 13 9 20 9"></polyline>
                </svg>
            </div>
            <span class="custom-radio-button bg-blue" style="margin-left: 10px; font-size: 10px;"></span>
            <span style="color: #778899; font-size: 12px; font-weight: 500; margin-left: 5px;">Post Requests</span>
        </label>
    </div>
@endif

        <hr style="width: 100%;border-bottom: 1px solid grey;">
        <div >
            <div class="row" style="max-height:auto">
                <div class="col " style="margin: 0px;">
                    <div class="input-group">
                        <input wire:model="search" id="filterSearch" onkeyup="filterDropdowns()" style="width:80%;font-size: 10px; border-radius: 5px 0 0 5px;" type="text" class="form-control" placeholder="Search...." aria-label="Search" aria-describedby="basic-addon1">
                        <button style="border-radius: 0 5px 5px 0; background-color: rgb(2, 17, 79); color: #fff; border: none;" class="search-btn" type="button">
                            <i style="text-align: center;color:white;margin-left:10px" class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="w-full visible mt-1" style="margin-top:20px;display:block">
                <div class="cus-button" style="display: flex; justify-content: space-between; width: 100%; padding: 0.5rem;" onclick="toggleDropdown('dropdownContent1', 'arrowSvg1')">
                    <span class="text-xs leading-4" style="font-weight:bold; color: grey;">Groups</span>
                    <span class="arrow-icon" id="arrowIcon1" style="margin-top:-5px">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down h-1.2x w-1.2x text-secondary-400" id="arrowSvg1" style="color:#3b4452;margin-top:-5px">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </span>
                </div>
                <div id="dropdownContent1" style="display: none;">
                    <ul class="d-flex flex-column" style="font-size: 12px; line-height: 1; text-decoration: none; color:black;text-align: left; padding-left: 0;overflow-y:auto;max-height:200px;overflow-x: hidden;">
                        <a class="menu-item" href="/Feeds" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">All Feeds</a>
                        @if (Auth::guard('hr')->check())
                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Every One</a>
                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Every One</a>
                        @endif
                        @if (Auth::guard('hr')->check())
                        <a class="menu-item" href="/Feeds" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Events</a>
                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/Feeds" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Events</a>
                        @endif
                        @if (Auth::guard('hr')->check())
                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Company News</a>
                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Company News</a>
                        @endif
                        @if (Auth::guard('hr')->check())
                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Appreciation</a>
                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Appreciation</a>
                        @endif
                        @if (Auth::guard('hr')->check())
                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Buy/Sell/Rent</a>
                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Buy/Sell/Rent</a>
                        @endif
                    </ul>
                </div>
            </div>


            <div class="w-full visible mt-1" style="margin-top: 20px;display:block;">
                <div class="cus-button" style="display: flex; justify-content: space-between; width: 100%; padding: 0.5rem;">
                    <span class="text-xs leading-4 " style="font-weight: bold;color:grey">Location</span>
                    <span class="arrow-icon" id="arrowIcon2" onclick="toggleDropdown('dropdownContent2', 'arrowSvg2')" style="margin-top:-5px;color:#3b4452;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down h-1.2x w-1.2x text-secondary-400" id="arrowSvg2" style="color:#3b4452;margin-top:-5px">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </span>
                </div>
                <div id="dropdownContent2" style="font-size: 12px; line-height: 1; text-decoration: none; color:#3b4452; text-align: left; padding-left: 0; display: none;overflow-y:auto;max-height:200px;overflow-x: hidden;">
                    <ul class="d-flex flex-column" style="font-size: 12px; margin: 0; padding: 0;">
                        <b class="menu-item" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">India</b>

                        @if (Auth::guard('hr')->check())

                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Adilabad</a>

                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Adilabad</a>
                        @endif

                        @if (Auth::guard('hr')->check())

                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Doddaballapur</a>

                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Doddaballapur</a>
                        @endif
                        @if (Auth::guard('hr')->check())

                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Guntur</a>

                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Guntur</a>

                        @endif
                        
                        @if (Auth::guard('hr')->check())

                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Hoskote</a>

                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Hoskote</a>
                        @endif

                        @if (Auth::guard('hr')->check())

                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Hyderabad</a>

                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Hyderabad</a>
                        @endif
                        @if (Auth::guard('hr')->check())

<a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Mandya
</a>

@elseif (Auth::guard('emp')->check())
<a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Mandya
</a>
@endif
@if (Auth::guard('hr')->check())

<a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Mangalore
</a>

@elseif (Auth::guard('emp')->check())
<a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Mangalore
</a>
@endif
@if (Auth::guard('hr')->check())

<a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Mumbai
</a>

@elseif (Auth::guard('emp')->check())
<a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Mumbai
</a>
@endif
@if (Auth::guard('hr')->check())

<a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Mysore
</a>

@elseif (Auth::guard('emp')->check())
<a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Mysore
</a>
@endif
@if (Auth::guard('hr')->check())

<a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Pune
</a>

@elseif (Auth::guard('emp')->check())
<a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Pune
</a>
@endif
@if (Auth::guard('hr')->check())

<a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Sirsi
</a>

@elseif (Auth::guard('emp')->check())
<a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Sirsi
</a>
@endif
@if (Auth::guard('hr')->check())

<a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Thumkur
</a>

@elseif (Auth::guard('emp')->check())
<a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Thumkur
</a>
@endif
                        @if (Auth::guard('hr')->check())

                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Tirupati</a>

                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Tirupati</a>
                        @endif

                        @if (Auth::guard('hr')->check())

                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Trivandrum</a>

                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Trivandrum</a>
                        @endif
                        @if (Auth::guard('hr')->check())

<a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Udaipur</a>

@elseif (Auth::guard('emp')->check())
<a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Udaipur</a>
@endif
@if (Auth::guard('hr')->check())

<a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Vijayawada</a>

@elseif (Auth::guard('emp')->check())
<a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Vijayawada</a>
@endif
                        @if (Auth::guard('hr')->check())

                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;font-weight:700">USA</a>

                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;font-weight:700">USA</a>
                        @endif
                        @if (Auth::guard('hr')->check())

                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">California</a>

                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">California</a>
                        @endif
                        @if (Auth::guard('hr')->check())

                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">New York</a>

                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">New York</a>
                        @endif

                        @if (Auth::guard('hr')->check())

                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Hawaii</a>

                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Hawaii</a>
                        @endif

                    </ul>
                </div>
            </div>
            <div class="w-full visible mt-1" style="margin-top: 20px;display:block">
                <div class="cus-button" style="display: flex; justify-content: space-between; width: 100%; padding: 0.5rem;overflow-y:auto;max-height:fit-content;overflow-x: hidden">
                    <span class="text-xs leading-4" style="font-weight: bold; color: grey;">Department</span>
                    <span class="arrow-icon" id="arrowIcon3" onclick="toggleDropdown('dropdownContent3', 'arrowSvg3')" style="margin-top:-5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down h-1.2x w-1.2x text-secondary-400" id="arrowSvg3" style="color:#3b4452;">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </span>
                </div>
                <div id="dropdownContent3" style="font-size: 12px; line-height: 1; text-decoration: none; color: black; text-align: left; padding-left: 0; display: none;">
                    <ul class="d-flex flex-column" style="font-size: 12px; margin: 0; padding: 0;">
                        @if (Auth::guard('hr')->check())
                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">HR</a>
                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">HR</a>
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Operations Team</a>
                        @endif
                        @if (Auth::guard('hr')->check())
                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Operations</a>
                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Operations</a>
                        @endif
                        @if (Auth::guard('hr')->check())
                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Production Team</a>
                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Production Team</a>
                        @endif
                        @if (Auth::guard('hr')->check())
                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">QA</a>
                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">QA</a>
                        @endif
                        @if (Auth::guard('hr')->check())
                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Sales Team</a>
                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Sales Team</a>
                        @endif
                        @if (Auth::guard('hr')->check())
                        <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Testing Team</a>
                        @elseif (Auth::guard('emp')->check())
                        <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Testing Team</a>
                        @endif
                    </ul>
                </div>
            </div>

        </div>
    </div>
   
    <div class="col m-0" style="max-height: 100vh; overflow-y: auto;scroll-behavior: smooth;">
                    <div class="row align-items-center ">
                        <div class="col-md-5" style=" justify-content: flex-start;display:flex">
                            <div style="width: 2px; height: 40px; background-color: #97E8DF; margin-right: 10px;"></div>
                            <gt-heading _ngcontent-eff-c648="" size="md" class="ng-tns-c648-2 hydrated"></gt-heading>
                            <div class="medium-header border-cyan-200" style="margin-left:-1px">Post Requests</div>
                        </div>

                 


                    </div>
                    <div class="col-md-12">

                    <div id="eventsSection" style=" display: flex; flex-direction: column; align-items: center; justify-content: center; ">
                    @if($isManager)    
                    @if($posts->where('status', 'Pending')->isEmpty())
                        <img src="https://freesvg.org/img/request.png" alt="Empty Image" style="width: 250px; height: auto; display: block;">
                        <p style="font-size:20px;font-weight:500; text-align: center;color:#3b4452">It feels empty here!</p>
                        <p style="font-size:12px;color:#778899; text-align: center;">Your feed is still in making as there's no post requeststo show.</p>
                  

                    </div>

                    @else
                   
                    @foreach($posts->where('status', 'Pending') as $post)

                    <div class="col-md-12 bg-white" style="border-radius:5px;border:1px solid #ccc;height:auto;margin-top:10px">
                         <div class="row m-2">
                            <p style="font-weight:500;color:#3b4452;font-size:14px;margin-top:10px">Post Requests :</p>
                            <div class="col-md-4" style="font-size:12px;">
                               EmpID : {{ $post->emp_id }}
                               
                            </div>
                            <div class="col-md-7" style="font-size:12px">
                            @if($post->employeeDetails)
  Employee Name: {{ $post->employeeDetails->first_name }} {{ $post->employeeDetails->last_name }}
@else
 No employee details found for this post.
@endif
                               
                            </div>
                     
                            </div>
                            <div class="row m-2">
                            <div class="col-md-12 " style="font-size:12px">
                               Category : {{ $post->category }}
                               
                            </div>
                            </div>
                            <div class="row m-2">
                            <div class="col " style="font-size:12px">
                               Description : {{ $post->description }}
                               
                            </div>
                            </div>
                            <div class="row" style="display:flex">
                            <div class="col-md-5 m-2" style="font-size:12px">
                               @if ($post->getImageUrlAttribute())
                                <a href="#" wire:click.prevent="showImage('{{ $post->getImageUrlAttribute() }}')"
                                    style="text-decoration: none; color: #007BFF;margin-left:10px">
                                    View Image
                                </a>
                                @endif
                            </div>
                          
                                @if ($showImageDialog)
                            <div class="modal" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title viewfile">View File</h5>
                    </div>
                    <div class="modal-body text-center">
                    @if ($imageUrl)
                    <img src="{{ $imageUrl }}" alt="File" class="img-fluid" style="max-width: 100%;" />
                    @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="submit-btn"
                            wire:click.prevent="downloadImage">Download</button>
                        <button type="button" class="cancel-btn1" wire:click="closeImageDialog">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show blurred-backdrop"></div>
    
@endif
                               
<div class="col-md-5 m-2" style="font-size:12px">
<div class="row" style="display: flex; justify-content: space-between; margin-bottom: 8px;">
    @if($post->status !== 'Closed')
        <button 
            wire:click="closePost('{{ $post->id }}')" 
            style="background-color: rgb(2, 17, 79); color: white; border-radius: 5px; font-size:12px; border: 1px solid silver; width:70px; height:30px">
            Approve
        </button>
    @else
        <button 
            disabled
            style="background-color: gray; color: white; border-radius: 5px; font-size:12px; border: 1px solid silver; width:70px; height:30px">
            Closed
        </button>
    @endif
</div>

            </div>  
                         </div>
                    </div>
                    </div>
                    
@endforeach

@endif
            


                    @endif




                </div>




                <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script> -->
                <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
                <script>
                    document.addEventListener('livewire:load', function() {
                        // Listen for clicks on emoji triggers and toggle the emoji list
                        document.querySelectorAll('.emoji-trigger').forEach(trigger => {
                            trigger.addEventListener('click', function() {
                                var index = this.dataset.index;
                                var emojiList = document.getElementById('emoji-list-' + index);
                                emojiList.style.display = (emojiList.style.display === "none" || emojiList.style.display === "") ? "block" : "none";
                            });
                        });
                    })
                    function handleImageChange() {
        // Display a flash message
        showFlashMessage('File uploaded successfully!');
    }

    function showFlashMessage(message) {
        const container = document.getElementById('flash-message-container');
        container.textContent = message;
        container.style.fontSize = '0.75rem';
        container.style.display = 'block';

        // Hide the message after 3 seconds
        setTimeout(() => {
            container.style.display = 'none';
        }, 3000);
    }
                    // Hide emoji list when an emoji is selected
                    document.querySelectorAll('.emoji-option').forEach(option => {
                        option.addEventListener('click', function() {
                            document.querySelectorAll('.emoji-list').forEach(list => {
                                list.style.display = "none";
                            });
                        });
                    });

                    function showEmojiList(index) {
                        var emojiList = document.getElementById('emoji-list-' + index);
                        if (emojiList.style.display === "none" || emojiList.style.display === "") {
                            emojiList.style.display = "block";
                        } else {
                            emojiList.style.display = "none";
                        }
                    }

                    function comment(index) {
                        var div = document.getElementById('replyDiv_' + index);
                        if (div.style.display === 'none') {
                            div.style.display = 'flex';
                        } else {
                            div.style.display = 'none';
                        }
                    }






                    function subReply(index) {
                        var div = document.getElementById('subReplyDiv_' + index);
                        if (div.style.display === 'none') {
                            div.style.display = 'flex';
                        } else {
                            div.style.display = 'none';
                        }
                    }


                    document.querySelector('emoji-picker').addEventListener('emoji-click', event => console.log(event.detail));
                    // JavaScript function to toggle arrow icon visibility
                    // JavaScript function to toggle arrow icon and dropdown content visibility
                    // JavaScript function to toggle dropdown content visibility and arrow rotation
                    function toggleDropdown(contentId, arrowId) {
                        var dropdownContent = document.getElementById(contentId);
                        var arrowSvg = document.getElementById(arrowId);

                        if (dropdownContent.style.display === 'none') {
                            dropdownContent.style.display = 'block';
                            arrowSvg.style.transform = 'rotate(180deg)';
                        } else {
                            dropdownContent.style.display = 'none';
                            arrowSvg.style.transform = 'rotate(0deg)';
                        }
                    }


                    function reply(caller) {
                        var replyDiv = $(caller).siblings('.replyDiv');
                        $('.replyDiv').not(replyDiv).hide(); // Hide other replyDivs
                        replyDiv.toggle(); // Toggle display of clicked replyDiv
                    }


                    function react(reaction) {
                        // Handle reaction logic here, you can send it to the server or perform any other action
                        console.log('Reacted with: ' + reaction);
                    }
                </script>
                <script>
                    $(document).ready(function() {
                        $('input[name="radio"]').on('change', function() {
                            var url = $(this).data('url');
                            window.location.href = url;
                        });

                        // Ensures the corresponding radio button is selected based on current URL
                        var currentUrl = window.location.pathname;
                        $('input[name="radio"]').each(function() {
                            if ($(this).data('url') === currentUrl) {
                                $(this).prop('checked', true);
                            }
                        });

                        // Click handler for the custom radio label to trigger the radio input change
                        $('.custom-radio-label').on('click', function() {
                            $(this).find('input[type="radio"]').prop('checked', true).trigger('change');
                        });
                    });
                </script>
                <script>
                    function addEmoji(emoji) {
                        let inputEle = document.getElementById('input');

                        input.value += emoji;
                    }

                    function toggleEmojiDrawer() {
                        let drawer = document.getElementById('drawer');

                        if (drawer.classList.contains('hidden')) {
                            drawer.classList.remove('hidden');
                        } else {
                            drawer.classList.add('hidden');
                        }
                    }

                    function toggleDropdown(contentId, arrowId) {
                        var content = document.getElementById(contentId);
                        var arrow = document.getElementById(arrowId);

                        if (content.style.display === 'block') {
                            content.style.display = 'none';
                            arrow.classList.remove('rotate');
                        } else {
                            content.style.display = 'block';
                            arrow.classList.add('rotate');
                        }

                        // Close the dropdown when clicking on a link
                        content.addEventListener('click', function(event) {
                            if (event.target.tagName === 'A') {
                                content.style.display = 'none';
                                arrow.classList.remove('rotate');
                            }
                        });
                    }
                </script>
                <script>
                    tinymce.init({
                        height: 140,
                        selector: "textarea#mytextarea",
                        plugins: "emoticons",
                        toolbar: "emoticons",
                        toolbar_location: "bottom",
                        menubar: false,
                        setup: function(editor) {
                            editor.on('input', function() {
                                autoResizeTextarea();
                            });
                        }
                    });

                    function autoResizeTextarea() {
                        var textarea = document.getElementById('mytextarea');
                        textarea.style.height = '140';
                    }
                </script>
                @push('scripts')
                <script>
                    Livewire.on('commentAdded', () => {
                        // Reload comments after adding a new comment
                        Livewire.emit('refreshComments');
                    });
                </script>
                @endpush
                <script>
                    // Add event listener to menu items
                    const menuItems = document.querySelectorAll('.menu-item');
                    menuItems.forEach(item => {
                        item.addEventListener('click', function() {
                            // Remove background color from all menu items
                            menuItems.forEach(item => {
                                item.classList.remove('selected');
                            });
                            // Add background color to the clicked menu item
                            this.classList.add('selected');
                        });
                    });
                </script>
                <script>
                    function selectEmoji(emoji, empId) {
                        // Your existing logic to select an emoji

                        // Toggle the emoji list visibility using the showEmojiList function
                        showEmojiList();
                    }
                    // Function to show the emoji list when clicking on the smiley emoji
                    function showEmojiList(index) {
                        var emojiList = document.getElementById('emoji-list-' + index);
                        if (emojiList.style.display === "none") {
                            emojiList.style.display = "block";
                        } else {
                            emojiList.style.display = "none";
                        }
                    }
                </script>
                <script>
                    function addEmoji(emoji_reaction, empId) {
                        // Your existing logic to select an emoji

                        // Toggle the emoji list visibility using the showEmojiList function
                        showEmojiList();
                    }
                    // Function to show the emoji list when clicking on the smiley emoji
                    function showEmojiList(index) {
                        var emojiList = document.getElementById('emoji-list-' + index);
                        if (emojiList.style.display === "none") {
                            emojiList.style.display = "block";
                        } else {
                            emojiList.style.display = "none";
                        }
                    }
                </script>




                <script>
                    document.addEventListener('livewire:load', function() {
                        // Listen for clicks on emoji triggers and toggle the emoji list
                        document.querySelectorAll('.emoji-trigger').forEach(trigger => {
                            trigger.addEventListener('click', function() {
                                var index = this.dataset.index;
                                var emojiList = document.getElementById('emoji-list-' + index);
                                emojiList.style.display = (emojiList.style.display === "none" || emojiList.style.display === "") ? "block" : "none";
                            });
                        });

                        // Hide emoji list when an emoji is selected
                        document.querySelectorAll('.emoji-option').forEach(option => {
                            option.addEventListener('click', function() {
                                document.querySelectorAll('.emoji-list').forEach(list => {
                                    list.style.display = "none";
                                });
                            });
                        });
                    });
                </script>


                <script>
                    document.addEventListener('click', function(event) {
                        const dropdowns = document.querySelectorAll('.cus-button');
                        dropdowns.forEach(function(dropdown) {
                            if (!dropdown.contains(event.target)) {
                                const dropdownContent = dropdown.nextElementSibling;
                                dropdownContent.style.display = 'none';
                            }
                        });
                    });

                    function toggleDropdown(dropdownId, arrowId) {
                        const dropdownContent = document.getElementById(dropdownId);
                        const arrowSvg = document.getElementById(arrowId);

                        if (dropdownContent.style.display === 'block') {
                            dropdownContent.style.display = 'none';
                            arrowSvg.classList.remove('arrow-rotate');
                        } else {
                            dropdownContent.style.display = 'block';
                            arrowSvg.classList.add('arrow-rotate');
                        }
                    }
                </script>


                <script>
                    document.addEventListener('click', function(event) {
                        const dropdowns = document.querySelectorAll('.cus-button');
                        dropdowns.forEach(function(dropdown) {
                            if (!dropdown.contains(event.target)) {
                                const dropdownContent = dropdown.nextElementSibling;
                                dropdownContent.style.display = 'none';
                            }
                        });
                    });

                    function toggleDropdown(dropdownId, arrowId) {
                        const dropdownContent = document.getElementById(dropdownId);
                        const arrowSvg = document.getElementById(arrowId);

                        if (dropdownContent.style.display === 'block') {
                            dropdownContent.style.display = 'none';
                            arrowSvg.classList.remove('arrow-rotate');
                        } else {
                            dropdownContent.style.display = 'block';
                            arrowSvg.classList.add('arrow-rotate');
                        }
                    }
                </script>
                @push('scripts')
                <script src="dist/emoji-popover.umd.js"></script>
                <link rel="stylesheet" href="dist/style.css" />

                <script>
                    document.addEventListener('livewire:load', function() {
                        const el = new EmojiPopover({
                            button: '.picker',
                            targetElement: '.emoji-picker',
                            emojiList: [{
                                    value: '🤣',
                                    label: 'laugh and cry'
                                },
                                // Add more emoji objects here
                            ]
                        });

                        el.onSelect(l => {
                            document.querySelector(".emoji-picker").value += l;
                        });

                        // Toggle the emoji picker popover manually
                        document.querySelector('.picker').addEventListener('click', function() {
                            el.toggle();
                        });
                    });
                </script>
                @endpush


                <script>
                    function filterDropdowns() {
                        var input, filter, dropdownContents, dropdownContent, menuItems, a, j, hasMatch;
                        input = document.getElementById('filterSearch');
                        filter = input.value.toUpperCase();

                        // Select all dropdown content elements and corresponding arrow icons
                        var dropdownData = [{
                                content: document.getElementById('dropdownContent1'),
                                arrow: document.getElementById('arrowSvg1')
                            },
                            {
                                content: document.getElementById('dropdownContent2'),
                                arrow: document.getElementById('arrowSvg2')
                            },
                            {
                                content: document.getElementById('dropdownContent3'),
                                arrow: document.getElementById('arrowSvg3')
                            }
                        ];

                        // Loop through each dropdown content
                        dropdownData.forEach(function(item) {
                            dropdownContent = item.content;
                            menuItems = dropdownContent.getElementsByTagName('a');
                            hasMatch = false; // Reset match flag

                            // Loop through all menu items and hide/show based on the filter
                            for (j = 0; j < menuItems.length; j++) {
                                a = menuItems[j];
                                if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                                    a.style.display = ""; // Show matching item
                                    hasMatch = true; // Found a match
                                } else {
                                    a.style.display = "none"; // Hide non-matching item
                                }
                            }

                            // Show dropdown if there's at least one matching item
                            if (hasMatch) {
                                dropdownContent.style.display = "block"; // Show dropdown
                                item.arrow.style.transform = "rotate(180deg)"; // Rotate arrow to point upwards
                            } else {
                                dropdownContent.style.display = "none"; // Hide dropdown
                                item.arrow.style.transform = "rotate(0deg)"; // Reset arrow to point downwards
                            }
                        });
                    }
                </script>



                <script>
                    function handleRadioChange(element) {
                        const url = element.getAttribute('data-url');
                        window.location.href = url;
                    }
                </script>

            </div>
        </div>
        </div>
        </div>
        </div>