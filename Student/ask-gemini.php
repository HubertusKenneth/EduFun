<?php
header('Content-Type: application/json');

$api_key = 'AIzaSyCfYf2C5yEQHHPJgndZuuWSOoMVZhkqpLA';

$input = json_decode(file_get_contents("php://input"), true);
$question = $input['question'] ?? '';

if (!$question) {
    echo json_encode(["response" => "Question not found."]);
    exit;
}

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $api_key;

$payload = [
    "contents" => [
        [
            "parts" => [
                [
                    "text" => "kamu adalah chatbot dari software yang bernama EduFun, ini adalah deskripsi dari EduFun
                            sebuah software yang bertemakan learning management system dimana ada dua role dalam software tersebut, yaitu Teacher dan Students. Dalam software ini, Teacher dapat membuat sebuah kelas dan bisa menginvite Student lewat invitation link(randomize link),. Di dalam kelas tersebut terdapat page courses dimana teacher dapat mengupload materi terkait mata pelajarna kelas tersebut, terdapat juga forum discussion sebagai komunikasi antara student dan teacher, dan yang terakhir teacher dapat membuat sebuah game quiz (seperti quiziz) yang dapat dikerjakan oleh student agar dapat lebih mendalami materi yang ada. Di sisi lain, student dapat join kelas tersebut dan melihat materi yang diupload oleh teacher,dapat berdiskusi di forum, dan juga dapat mengerjaka soal quiz yang dibrikan oleh teacher. Lalu, akan terdapat juga chatbot yang berfungsi ketika User(teacher atau student) kurang mengerti cara menggunakan sotware tersebut.
                            Dalam software ini, user dapat membuat akun (teacher atau student) yang dimana data dari akun tersebut akan masuk ke dalam database.
                            kamu juga bisa menjawab dalam bahasa lain, sesuaikan dengan bahasa apa yang user gunakan. kamu juga bisa menjawab  pertanyaan lain di luar software ini, tetapi tetap fokus ke software ini. Kalau menjawab, jangan terlalu detail jika tidak sesuai dengan pertanyaan'"
                ],
                [
                    "text" => $question
                ]
            ]
        ]
    ]
];


$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($response === false) {
    echo json_encode(["response" => "Failed to contact Gemini server. Error: $error"]);
    exit;
}

$responseData = json_decode($response, true);

if (!isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
    echo json_encode(["response" => "Response is not formatted. HTTP status: $http_code"]);
    exit;
}

$answer = $responseData['candidates'][0]['content']['parts'][0]['text'];
echo json_encode(["response" => $answer]);
