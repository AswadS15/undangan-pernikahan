<?php
    include "database.php";

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $kehadiran = $_POST['kehadiran'];
    $komen = $_POST['komen'];
    $waktu = date('Y-m-d H:i:s'); 

    // Buat query untuk memasukkan data ke dalam tabel tamus
    $sql = "INSERT INTO tamus (nama, kehadiran, komen, created_at) VALUES (?, ?, ?, ?)";

    // Siapkan statement
    $stmt = $conn->prepare($sql);

    // Bind parameter
    $stmt->bind_param("ssss", $nama, $kehadiran, $komen, $waktu);

    // Tutup statement
    $stmt->close();
}

// Fungsi untuk menghitung waktu yang telah berlalu
function waktu_yang_lalu($timestamp) {
    $now = time();
    $past = strtotime($timestamp);
    $diff = $now - $past;
    
    if ($diff < 60) {
        return 'baru saja'; // Jika waktu kurang dari 1 menit
    } elseif ($diff < 3600) {
        $minutes = round($diff / 60);
        return $minutes . ' menit yang lalu';
    } elseif ($diff < 86400) {
        $hours = round($diff / 3600);
        return $hours . ' jam yang lalu';
    } elseif ($diff < 604800) {
        $days = round($diff / 86400);
        return $days . ' hari yang lalu';
    } else {
        return date('d M Y', $past); // Format default jika lebih dari 1 minggu
    }
}

$sql = "SELECT nama, kehadiran, komen, created_at FROM tamus ORDER BY created_at DESC";
$result = $conn->query($sql);

// Cek jika query berhasil
if (!$result) {
    die("Query gagal: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/icon/css/all.min.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="/public/css/final.css">
    <title>UNDANGAN ONLINE</title>
    <style>
        .d-none{
            display: none;
        }
        .welcome {
            background-image: url(/public/mempelai1.jpg);
        } 

        .sec1 {
            text-shadow: 2px 2px black;
        }
        .section2{
            background-image: url(/public/mempelai1.jpg);
            background-position: center;
            background-size: cover;
        }
        .wanita {
            background-image: url(/public/wanita2.jpg);
            background-position: center;
            background-size: cover;
        }
        .pria {
            background-image: url(/public/pria2.jpg);
            background-position: center;
            background-size: cover;
        }
        @keyframes fadeOut {
        0% {
            opacity: 1;
            transform: scale(1);
        }
        100% {
            opacity: 0;
            transform: scale(0.95);
        }
        }

        .fade-out {
        animation: fadeOut 0.5s ease-out forwards;
        }

        .slide {
            background-image: url(/public/mempelai1jpeg.jpeg);
            background-repeat: no-repeat;
            transition: 1s;

            animation-name: slideshow;
            animation-direction: alternate-reverse;
            animation-play-state: running;
            animation-timing-function: ease-out;
            animation-duration: 20s;
            animation-fill-mode: backwards;
            animation-iteration-count: infinite;
        }
        @keyframes slideshow {
            0%{
                background-image: url(/public/mempelai2.jpeg);
            }
            20%{
                background-image: url(/public/mempelai3.jpg);
            }
            40%{
                background-image: url(/public/mempelai4.jpg);
            }
            60%{
                background-image: url(/public/mempelai5.jpeg);
            }
            80%{
                background-image: url(/public/mempelai6.jpg);
            }
            100%{
                background-image: url(/public/mempelai1jpeg.jpeg);
            }
            0%{
                background-image: url(/public/mempelai2.jpeg);
            }
        }

        .disc {
            animation: putar 4s linear infinite;
        }
        @keyframes putar {
            from {
                transform: rotate(0);
            }
            to {
                transform: rotate(360deg);
            }
        }

         /* Styling untuk batas kata di textarea */
        .word-limit {
            position: relative;
        }
        .word-limit .word-count {
            position: absolute;
            bottom: 0;
            right: 0;
            font-size: 0.75rem;
            color: gray;
        }
    </style>
</head>
<body>
    <!-- {{-- MAIN HALAMAN--}} -->
    <main>
        <!-- {{-- SECTION 1 --}} -->
            <section id="home" class="relative  h-screen w-full slide bg-opacity-10 bg-cover bg-center">
                <div class="bg-cover bg-gradient-to-t bg-fixed from-black/75 to-transparent bg-opacity-30 w-full h-screen absolute z-10 top-0">
                    <div class="h-screen flex flex-col justify-center items-center relative"> 
                        <div class="h-screen py-32 flex flex-col justify-between items-center">
                            <div class="text-white font-bold text-center sec1">
                                <h3 class="font-light text-sm">THE WEDDING OF</h3>
                                <h3 class="font-cinzel text-2xl">Mahmud & Irma</h3>
                                <p class="text-xs">~ Sabtu, 24 Agustus 2024 ~</p>
                            </div>
                            <div class="justify-center items-center gap-10 text-center text-2xl text-white">
                                <div class="flex font-semibold gap-3 sec1">
                                    <div class=" w-[70px] border-2 border-white h-[70px]  flex justify-center items-center rounded-xl  p-5 ">
                                        <span id="days"></span>
                                    </div>
                                    <div class=" w-[70px]  border-2 border-white h-[70px]  flex justify-center items-center rounded-xl  p-5 ">
                                        <span id="hours"></span>
                                    </div>
                                    <div class=" w-[70px]  border-2 border-white h-[70px]  flex justify-center items-center rounded-xl  p-5 ">
                                        <span id="minutes"></span>
                                    </div>
                                    <div class=" w-[70px]  border-2 border-white h-[70px]  flex justify-center items-center rounded-xl  p-5 ">
                                        <span id="seconds"></span>
                                    </div>
                                </div>  
                            </div>
                        </div>  
                    </div>   
                </div>      
            </section>
        <!-- {{-- END SECTION 1 --}} -->
          
        <!-- {{-- SECTION 2 --}} -->
            <section id="section2" class=" bg-cover bg-center text-center relative text-black/50">
                <div class="w-full px-5">
                    <h3 class="text-2xl font-semibold font-cinzel py-10">~ Our Wedding ~</h3>           
                    <div class="bg-white/35 backdrop-blur-sm drop-shadow-sm rounded-xl py-5 px-3">
                        <p class="text-sm">Atas Rahmat Allah Yang Maha Kuasa, kami bermaksud mengundang Anda di acara kami. Merupakan suatu kehormatan dan kebahagiaan bagi kami sekeluarga, apabila Bapak/ibu/Saudara/i berkenan hadir dan membarikan doa restu pada
                        </p>
                        <div class=" w-full h-[500px] rounded-t-[300px] rounded-b-[100px] mx-auto bg-cover bg-center my-10" style="background-image: url(/public/mempelai5.jpeg)" >
                        </div>
                        <p class="text-sm">Di antara tanda-tanda (kebesaran)-nya ialah bahwa Dia menciptakan pasangan-pasangan untukmu dari (jenis) dirimu sendiri agar kamu merasa tentram kepadanya. Dia menjadikan di antaramu rasa cinta dan kasih sayang. Sesungguhnya pada yang demikian itu benar-benar terdapat tanda-tanda (kebesaran Allah) bagi kaum yang berfikir.
                        </p>
                        <h3 class=" font-bold text-base pt-5">- Ar-Rum. Ayat 21 -</h3>
                    </div>
                </div>   
            </section>
        <!-- {{-- END SECTION 2 --}} -->
        <!-- {{-- SECTION 3 --}} -->
            <section id="mempelai" class="section2 bg-fixed h-[1000px] bg-cover bg-center text-center relative overflow-hidden text-black/50 mt-10">
                <div class="bg-cover bg-gradient-to-b bg-fixed from-white/50 to-black/50 w-full h-full absolute z-10 top-0">
                    <div class="w-full h-full  p-3 box-border">
                        <div class="w-full h-full rounded-xl border-2 border-white p-1 bg-white bg-opacity-30">
                            <div class="w-full h-full border-2 border-white rounded-lg p-2 box-border">
                                <div class="w-full flex flex-col items-center">
                                    <div class="pria w-full h-[250px] rounded-md">
                                    </div>
                                    <div class="py-10">
                                        <h3 class="font-cinzel text-3xl leading-none font-semibold">Mustapa</h3>
                                        <h3 class="font-cinzel text-lg leading-none font-semibold">Mahmud, SP</h3>
                                        <p class="text-sm   pt-2">Putra Pertama dari
                                            <br>Bapak Umar Mahmud &
                                            <br>Ibu Sitria Husa
                                        </p>
                                    </div>
                                </div>
                                <div class="w-full flex flex-col items-center">
                                    <div class="wanita w-full h-[250px] rounded-md">
                                    </div>
                                    <div class="py-10">
                                        <h3 class="font-cinzel text-3xl leading-none font-semibold">Ade Irma</h3>
                                        <h3 class="font-cinzel text-lg leading-none font-semibold">Suryani M. Rauf, SM</h3>
                                        <p class="text-sm   pt-2">Putri Pertama dari
                                            <br>Bapak Marwan G. Rauf &
                                            <br>Ibu Nina Duna Musa
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <!-- {{-- END SECTION 3 --}} -->

        <!-- {{-- SECTION 4 --}} -->
            <section id="maps" class="bg-cover bg-center text-center relative  px-7 text-black/50">
                <h3 class="text-center font-cinzel text-2xl font-semibold py-10">~ Save Date ~</h3>
                <div class="flex flex-col gap-5">
                    <div class="w-max-[22rem] border border-black border-opacity-10 rounded-lg drop-shadow-lg  shadow-lg py-5">
                        <h3 class="text-xl font-light text font-cinzel">Akad Nikah</h3>
                        <div class="text-xs font-light flex flex-col gap-3">
                            <p class="font-semibold">Sabtu, 24 Agustus 2024</p>
                            <p>Pukul 09.00 Wita</p>
                            <p>Rumah Mempelai Wanita di<br>Kelurahan Kayumera</p>
                            <a href="https://maps.app.goo.gl/UEg7D7BLuvJEG9cVA"><span class="py-2 px-3 border-b-4 border-black/50 rounded-xl text-lg font-semibold transition-all">Lihat Lokasi</span></a>
                        </div>
                    </div>
                    <div class="w-max-[22rem] border border-black border-opacity-10 rounded-lg drop-shadow-lg  shadow-lg py-5">
                        <h3 class="text-xl font-light text font-cinzel">Resepsi Pernikahan</h3>
                        <div class="text-xs font-light flex flex-col gap-3">
                            <p class="font-semibold">Sabtu, 24 Agustus 2024</p>
                            <p>Pukul 19.00 Wita</p>
                            <p>Rumah Mempelai Wanita di<br>Kelurahan Kayumera</p>
                            <a href="https://maps.app.goo.gl/UEg7D7BLuvJEG9cVA"><span class="py-2 px-3 border-b-4 border-black/50 rounded-xl text-lg font-semibold transition-all">Lihat Lokasi</span></a>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col pb-10 text-center max-w-[22rem] mx-auto">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3989.575042162276!2d122.97467367496493!3d0.6327247993611026!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMMKwMzcnNTcuOCJOIDEyMsKwNTgnMzguMSJF!5e0!3m2!1sid!2sid!4v1723048201397!5m2!1sid!2sid" width="600" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="mx-auto w-auto mt-5"></iframe>
                </div>
                <div>
                    <p class="text-sm py-10">Besar harapan kami jika Bapak/Ibu/Saudara/i berkenan hadir pada acara ini. Atas perhatiannya kami keluarga besar mengucapkan<br>
                    <span class="text-lg font-semibold">Terima Kasih.</span></p>
                </div>
            </section>
        <!-- {{-- END SECTION 4 --}} -->

        <!-- {{-- SECTION 5 --}} -->
            <section id="galery" class="w-full bg bg-cover bg-center text-center relative overflow-hidden px-7 text-black/50">
                <h3 class="text-center font-cinzel text-2xl font-semibold py-10">Our Gallery</h3>
                <div class="grid grid-cols-2 gap-3 mt-5 place-items-center">
                    <div class="col-span-2">
                        <img src="/public/mempelai4.jpeg" alt="">
                    </div>
                    <div class="col-span-1">
                        <img src="/public/mempelai1jpeg.jpeg" alt="">
                    </div>
                    <div class="col-span-1">
                        <img src="/public/mempelai3.jpeg" alt="">
                    </div>
                    <div class="col-span-1">
                        <img src="/public/mempelai5.jpeg" alt="">
                    </div>
                    <div class="col-span-1">
                        <img src="/public/mempelai7.jpeg" alt="">
                    </div>
                    <div class="col-span-2">
                        <img src="/public/mempelai6.jpeg" alt="">
                    </div>
                </div>
            </section>
        <!-- {{-- END SECTION 5 --}} -->
        <!-- SECTION 6 -->
            <section id="maps" class="h-[1000px] text-center relative px-7 text-black/50">
                <h3 class="text-center font-cinzel text-2xl font-semibold py-10">~ Ucapan Selamat ~</h3>   
                <div class=" rounded-lg backdrop-blur-lg max-w-lg w-full p-5 border-2 border-black/35 mx-auto">
                    <form action="index.php" method="POST" class="max-w-lg mx-auto p-4 bg-white shadow-md rounded">
                        <div class="mb-4">
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama:</label>
                            <input type="text" id="nama" name="nama" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-black/35 focus:border-black/35 sm:text-sm">
                        </div>

                        <div class="mb-4">
                            <label for="kehadiran" class="block text-sm font-medium text-gray-700">Kehadiran:</label>
                            <select id="kehadiran" name="kehadiran" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-black/35 focus:border-black/35 sm:text-sm">
                            <option value="Hadir">Hadir</option>
                            <option value="Tidak Hadir">Tidak Hadir</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="komen" class="block text-sm font-medium text-gray-700">Komentar:</label>
                            <textarea id="komen" name="komen" maxlength="500" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-black/35 focus:border-black/35 sm:text-sm"
                                    ></textarea>
                        </div>

                        <button type="submit"
                                class="w-full bg-black/35 text-white py-2 px-4 rounded-md hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black/35">
                            Kirim Ucapan
                        </button>
                    </form>                    
                    <div id="isikomen" class="w-full h-[300px] mt-10 rounded-lg border-2 border-black/35 px-2 py-4 overflow-y-scroll">
                        <div class="flex flex-col gap-2">
                            <div class="w-full">
                                <?php
                                // Tampilkan data dari hasil query
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $nama = htmlspecialchars($row['nama']);
                                        $kehadiran = htmlspecialchars($row['kehadiran']);
                                        $komen = htmlspecialchars($row['komen']);
                                        $created_at = $row['created_at'];

                                        echo '<div class="flex gap-3">';
                                        echo '    <div class="flex flex-col">';
                                        echo '        <span><i class="fa-solid fa-user text-3xl"></i></span>';
                                        echo '    </div>';
                                        echo '    <div class="w-full px-5">';
                                        echo '        <div class="flex gap-5 items-center justify-between">';
                                        echo '          <h3 class="font-bold text-sm text-left">' . $nama . '</h3>';
                                        echo '          <p class="text-xs text-left">' . $kehadiran . '</p>';
                                        echo '        </div>';
                                        echo '        <p class="text-sm text-left text-black">' . $komen . '</p>';
                                        echo '        <p class="text-xs text-left text-black/30 pb-2">' . waktu_yang_lalu($created_at) . '</p>';
                                        echo '    </div>';
                                        echo '</div>';
                                        echo '<hr>';
                                    }
                                } else {
                                    echo '<p class="text-center">Tidak ada data</p>';
                                }

                                // Tutup koneksi
                                $conn->close();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <!-- END SECTION 6 -->
<!-- {{-- NAVBAR --}} -->
    <nav class=" rounded-lg px-3 py-2 bg-white/35 border-2 border-white backdrop-blur-sm fixed bottom-5 right-5 left-5 font-semibold font-work text-sm z-20 drop-shadow-lg">
        <div class="flex justify-between text-xl items-center text-black/50 leading-none">
            <div>
                <a href="#home"><i class="fa fa-home"></i></a>
            </div>
            <div>
                <a href="#mempelai"><i class="fa-solid fa-user-group"></i></a>
            </div>
            <div>
                <a href="#maps"><i class="fa-solid fa-map-location-dot"></i></a>
            </div>
            <div>
                <a href="#galery"><i class="fa-regular fa-image"></i></a>
            </div>
            <div>
                <button id="toggleScroll"><i class="fa-regular fa-circle-down"></i></button>
            </div>
            <div id="pembungkusAudio" class="flex justify-center items-center">
                <audio id="song" autoplay loop>
                    <source src="/public/audio/music.mp3" type="audio/mp3">
                </audio>
                <button><i id="disc" class="disc fa-regular fa-circle-pause"></i></button>
                <i class=""></i>
            </div>

        </div>
    </nav>
    
<!-- {{-- END NAVBAR --}} -->
    </main>
<!-- {{-- END MAIN HALAMAN --}} -->

<!-- {{-- COVER HALAMAN UNDANGAN --}} -->
    <div id="myContainer" class="welcome d-none w-full z-50 h-screen top-0 bg-center bg-cover fixed overflow-hidden">
        <div class="bg-cover bg-gradient-to-t bg-fixed from-black/75 to-transparent bg-opacity-30 w-full h-screen absolute z-10 top-0">
            <div class="flex flex-col items-center justify-between h-full w-full py-20">
                <div class=" text-center flex flex-col p-5 font-extrabold text-white sec1">
                    <h3 class="font-cinzel text-3xl ">Mahmud & Irma</h3>
                    <p class="text-sm">Sabtu, 24 Agustus 2024</p>
                    <p class="mt-2 tex text-xs font-normal">Tanpa Mengurang Rasa Hormat, Kami Mengundang
                        <br>Bapak/Ibu/Saudara/i untuk Hadir di Acara Kami.
                    </p>
                </div>
                <div class="flex flex-col text-center text-white px-3 gap-5">
                    <div class="font-semibold">
                        <p class="font-light">Kepada Bpk/Ibu/Saudara/i</p>
                        <p class="nama py-1 font-cinzel"><span></span></p>
                        <p class="font-light">Bersama</p>
                        <p class="bersama font-cinzel"><span></span></p>
                    </div>
                    <button  id="hideButton" class="mx-auto"><span class="p-2 hover:bg-black/50 font-semibold border hover:text-white text-white transition-all rounded-md text-black/50 shadow-md">Buka Undangan</span></button>
                    <p class="text-xs">Mohon maaf apabila ada kesalahan nama dan gelar</p>
                </div>    
            </div>
        </div>         
    </div>
<!-- {{-- END COVER HALAMAN UNDANGAN --}} -->

<!-- {{-- JAVA SCRIPT --}} -->
        <!-- {{-- WAKTU PERNIKAHAN --}} -->
            <script>
                // Target date for the countdown in WITA (August 26, 2024 00:00:00 WITA)
                const targetUTCDate = new Date('2024-08-25T16:00:00Z').getTime(); // 26 Agustus 2024 00:00:00 WITA

                // Update the countdown every second
                const countdown = setInterval(function() {
                    // Get current date and time
                    const now = new Date().getTime();

                    // Calculate the remaining time in milliseconds
                    const distance = targetUTCDate - now;

                    // Calculate days, hours, minutes, and seconds
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Display the countdown
                    document.getElementById('days').innerHTML = `${days}d`;
                    document.getElementById('hours').innerHTML = `${hours}h`;
                    document.getElementById('minutes').innerHTML = `${minutes}m`;
                    document.getElementById('seconds').innerHTML = `${seconds}s`;

                    // If the countdown is over, display a message
                    if (distance < 0) {
                        clearInterval(countdown);
                        document.getElementById('countdown').innerHTML = 'EXPIRED';
                    }
                }, 1000); // Update every second (1000 milliseconds)
            </script>
        <!-- {{-- END WAKTU PERNIKAHAN --}} -->

        <!-- {{-- DISABLESCROLL COVER --}} -->
            <script>
                // function playAudio{
                //     const song = document.querySelector('#song');
                //     song.volume = 0.1
                //     song.play();
                //  }
                const container = document.getElementById('myContainer');
                const disc = document.getElementById('disc')
                const icon = document.querySelector('#pembungkusAudio i')
                let play = false;

                document.getElementById('hideButton').addEventListener('click', function() { 
                    // Menambahkan kelas animasi
                    container.classList.add('fade-out');
                    song.play();
                    play = true
                    song.volume = 0.1;
                    // Menunggu animasi selesai sebelum menghilangkan kontainer dari tampilan
                    setTimeout(() => {
                        container.style.display = 'none';
                    }, 500); // Durasi 0.5 detik sesuai dengan durasi animasi
                });

                disc.onclick = function() {
                    if (play) {
                        song.pause();
                        icon.classList.remove('fa-circle-play')
                        icon.classList.add('fa-circle-pause')
                    } else {
                        song.play();
                        icon.classList.remove('fa-circle-pause')
                        icon.classList.add('fa-circle-play')
                    }

                    play = !play;
                }

            </script>
        <!-- {{-- END DISABLESCROLL COVER --}} -->
        <!-- NAMA PENGUNJUNG -->
            <script>
                const urlParams = new URLSearchParams(window.location.search);
                const nama = urlParams.get('n') || 'Hamba Allah';
                const bersama = urlParams.get('b') || 'Hamba Allah Lainnya';

                const isiNama = document.querySelector('.nama span');
                const isiBersama = document.querySelector('.bersama span');

                // Mengisi teks dalam span dengan nilai dari parameter URL
                isiNama.innerText = nama.replace(/,$/, '');
                isiBersama.innerText = bersama.replace(/,$/, '');
            </script>
        <!-- END NAMA PENGUNJUNG -->
        <!-- BATAS KOMENTAR -->
            <script>
                const textarea = document.getElementById('comment');
                const wordCountDisplay = document.getElementById('wordCount');
                const maxWords = 25;
                
                textarea.addEventListener('input', function() {
                    const text = textarea.value;
                    const words = text.trim().split(/\s+/).length;
                    const wordCount = words > maxWords ? maxWords : words;
                    wordCountDisplay.textContent = `${wordCount} / ${maxWords}`;
                });
            </script>
        <!-- END BATAS KOMENTAR -->

            <script>
                document.getElementById('commentForm').addEventListener('submit', function(event) {
                    event.preventDefault();

                    // Ambil data dari form
                    const nama = document.getElementById('nama').value;
                    const kehadiran = document.getElementById('kehadiran').value;
                    const komen = document.getElementById('komen').value;

                    // Buat elemen baru untuk komentar
                    const commentElement = document.createElement('div');
                    commentElement.classList.add('bg-gray-100', 'p-4', 'my-2', 'rounded');

                    commentElement.innerHTML = `
                        <p class="text-sm text-gray-600">Dikirim oleh: ${nama} (${kehadiran})</p>
                        <p class="mt-2">${komen}</p>
                    `;

                    // Tambahkan elemen komentar ke dalam #isikomen
                    document.getElementById('isikomen').appendChild(commentElement);

                    // Reset form setelah submit
                    document.getElementById('commentForm').reset();
                });
            </script>
<!-- {{-- END JAVA SCRIPT --}} -->
</body>
</html>