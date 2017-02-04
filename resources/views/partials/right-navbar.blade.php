<!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">Login</a></li>
                            <li><a href="{{ url('/register') }}">Register</a></li>
                        @else
							<!-- If the user has the edit role -->
							@if(Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\CollectionController@show")
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
										Collection <span class="caret"></span>
									</a>
									<ul class="dropdown-menu" role="menu">
										<li><a href="/chapter/create/{{$collection->id}}">Add Chapter</a></li>
										<li><a href="/volume/create/{{$collection->id}}">Add Volume</a><li>
										<li><a href="/collection/{{$collection->id}}/edit">Edit Collection</a><li>
										<li><a href="">Delete Collection</a></li>
									</ul>
								</li>
							@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\CollectionController@edit")
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
										Collection <span class="caret"></span>
									</a>
									<ul class="dropdown-menu" role="menu">
										<li><a href="/collection/{{$collection->id}}/">View Collection</a><li>
										<li><a href="/chapter/create/{{$collection->id}}">Add Chapter</a></li>
										<li><a href="/volume/create/{{$collection->id}}">Add Volume</a><li>
										<li><a href="">Delete Collection</a></li>
									</ul>
								</li>
							@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\VolumeController@edit")
							<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
										Volume <span class="caret"></span>
									</a>
									<ul class="dropdown-menu" role="menu">
										<li><a href="">Delete Volume</a></li>
									</ul>
								</li>
							@elseif (Route::getCurrentRoute()->getActionName() == "App\\Http\\Controllers\\ChapterController@edit")
							<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
										Chapter <span class="caret"></span>
									</a>
									<ul class="dropdown-menu" role="menu">
										<li><a href="/chapter/{{$chapter->id}}/">View Chapter</a><li>
										<li><a href="">Delete Chapter</a></li>
									</ul>
								</li>
							@else
								<li><a href="{{url('/collection/create')}}">Create Collection</a></li>
							@endif
							
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
										<a href="{{url('/home')}}">User Dashboard</a>
									</li>
									<li>
                                        <a href="{{ url('/logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif