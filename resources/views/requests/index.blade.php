@extends('Labcoat::layouts/standard')
@section('page-title')
  EOS Requests
@endsection
@section('main-content')

  <div class="content">
    <div>
      <br>
      <br>
      <div class="indent-padding width-limited-1200">
        <a class="pull-right btn btn-primary btn-gradient" href="/requests/create">New Request</a><br><br>
        <div class="table-header eos">
          <p class="table-title">EOS Requests</p>
          <p class="table-sub-title">The list of current EOS requests.</p>
        </div>
      <table class="indexTable">
          <thead>
            <tr>
            <th>
              ID
            </th>
            <th>
              Name
            </th>
            <th>
              Submitted
            </th>
            <th>
              Status
            </th>
            <th>
              Name
            </th>
            {{-- <th>
              Project
            </th> --}}
            <th>
               STL File
            </th>
            <th>
               Volume
            </th>
            <th>
              P
            </th>
            <th>
              C
            </th>
            <th>
              T
            </th>
            <th>
              M
            </th>
            <th>
              #
            </th>
              <th>
                Change
              </th>
              <th>
                Reject
              </th>
              <th>
                Delete
              </th>
            </tr>
          </thead>
            @foreach($eosrequests as $eos)
          <tr class="topRow">
              <td class="id-td" rowspan="2">
                {{ $eos->id }}
              </td>
              <td class="nameDiv">
                <span title="{{ $eos->users->name }}">
                {{ str_limit($eos->users->name, 7) }}
                </span>
              </td>
              <td>{{ $eos->created_at}}</td>
              <td>
                <span title="Current Status">
                @if($eos->status === 0)
                  Pending
                @endif
                @if($eos->status === 1)
                  In Process
                @endif
                @if($eos->status === 2)
                  Complete
                @endif
                @if($eos->status === 3)
                  Rejected
                @endif
              </span>
              </td>
              <td>
                @if($eos->status === 0 || $eos->status === 1 || $eos->status === 3)
                  <a href="requests/{{ $eos->id }}/edit" title="Edit this request">
                    {{ $eos->name }}
                  </a>
                @else
                  <a href="requests/{{ $eos->id }}" title="View this request">
                    {{ $eos->name }}
                  </a>
                @endif
              </td>
              <td>
                <span title="{{$eos->stl}}">
                  {{ str_limit($eos->stl, 7) }}
                </span>
              </td>
              <td>
                <span title="{{$eos->dimX}} x {{$eos->dimY}} x {{$eos->dimZ}}">
                  {{ $eos->volume}}
                </span>
              </td>
              <td>
                @if($eos->project_id == 0)
                <span title="No project">
                  N
                </span>
                @else
                <span title="{{$projects[$eos->project_id]}}">
                  Y
                </span>
                @endif
              </td>
              <td>
                @if( $eos->clean === 1 )
                  <span title="Perfom post building cleaning">
                    Y
                  </span>
                @else
                  <span title="NO post build cleaning">
                    N
                  </span>
                @endif
              </td>
              <td>
                @if($eos->threads === 1)
                  <span title="Has threads">
                    Y
                  </span>
                @else
                  <span title="NO threads">
                    N
                  </span>
                @endif
              </td>
              <td>
                @if($eos->hinges === 1)
                  <span title="Has hinges or other moving parts">
                    Y
                  </span>
                @else
                  <span title="NO hinges or other moving parts">
                    N
                  </span>
                @endif
              </td>
              <td>
                <span title="{{ $eos->number_of_parts }} part(s)">
                  {{ $eos->number_of_parts }}
                </span>
              </td>
                <td>
                  @if($eos->status === 0)
                    {!! Form::open(['method' => 'POST', 'url' => 'change/' . $eos->id ]) !!}
                    <button type="submit" class="btn btn-warning btn-gradient" >
                      In Process
                    </button>
                    {!! Form::close() !!}
                  @elseif ($eos->status === 1)
                    {!! Form::open(['method' => 'POST', 'url' => 'change/' . $eos->id ]) !!}
                    <button type="submit" class="btn btn-warning btn-gradient" >
                      Complete
                    </button>
                    {!! Form::close() !!}
                  @endif
                </td>
                <td>
                  @if($eos->status == 0)
                  {{-- {!! Form::open(['method' => 'POST', 'url' => 'reject/' . $eos->id ]) !!}
                  <button type="submit" class="btn btn-danger btn-gradient" name="reject">
                    Reject
                  </button>
                  {!! Form::close() !!} --}}
                  <button type="submit" data-modal-url="{{ URL::route('request.reject', ['id' => $eos->id]) }}" class="btn btn-danger btn-gradient" data-modal-id="reject-{{ $eos->id }}" >Reject</button>
                  @endif
                </td>
                  <td align='center'>
                  @if($eos->status === 0 || $eos->status === 3)
                    <a href="javascript:undefined;" class="fa fa-fw fa-trash" style="text-decoration: none;" data-delete-url="{{ URL::route('request.destroy', $eos['id']) }}"></a>
                  @endif
                  </td>
            </tr>
            <tr class="hackAttack">
              <td class="fileLink" colspan="2"><a download title="Download .STL file" href="{{$eos->filePath}}">{{ $eos->stl}}</a></td>
              <td colspan="13"><span title="{{$eos->description}}">{{ str_limit($eos->description, 10) }}</span></td>
            </tr>
        @endforeach
     </table>
   </div>
   </div>

 </div>
 <script>
  
 </script>
@stop
{{-- <button type="submit" data-modal-url="{{ URL::route('request.changeStatus', ['id' => $eos->id]) }}" class="btn btn-warning btn-gradient" data-modal-id="changeStatus-{{ $eos->id }}" >
Change   ****->default(0);
</button> --}}
