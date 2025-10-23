@php
    $logoPath = public_path('images/votemaster.png');
    $logoBase64 = '';
    if (file_exists($logoPath)) {
        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
        $data = file_get_contents($logoPath);
        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Vote Receipt - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 30px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #09182D;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header img {
            height: 120px;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 28px;
            margin: 0;
            color: #09182D;
        }

        .header p {
            font-size: 14px;
            margin: 5px 0 0;
        }

        .info {
            margin-bottom: 20px;
            font-size: 14px;
        }

        .info strong {
            color: #09182D;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: left;
        }

        table th {
            background-color: #09182D;
            color: #fff;
        }

        .footer {
            text-align: center;
            font-size: 13px;
            margin-top: 40px;
            color: #777;
        }
    </style>
</head>

<body>

    <div class="header">
        @if($logoBase64)
            <img src="{{ $logoBase64 }}" alt="VoteMaster Logo">
        @endif
        <h1>Official Voting Receipt</h1>
        <p>Generated on {{ now()->format('F d, Y h:i A') }}</p>
    </div>

    <div class="info">
        <p><strong>Voter Name:</strong> {{ $user->name }}</p>
        <p><strong>Voter ID:</strong> {{ $user->voter_id ?? 'N/A' }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Election Title</th>
                <th>Candidate</th>
                <th>Position</th>
                <th>Date Voted</th>
            </tr>
        </thead>
        <tbody>
            @foreach($votes as $index => $vote)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $vote->election->title ?? 'N/A' }}</td>
                    <td>{{ $vote->candidate->name ?? 'N/A' }}</td>
                    <td>{{ $vote->candidate->position ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($vote->created_at)->format('F d, Y h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} Voting Management System</p>
    </div>

</body>

</html>
