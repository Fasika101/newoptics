<?php require_once('header.php'); ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $pgallery_title = $row['pgallery_title'];
    $pgallery_banner = $row['pgallery_banner'];
}
?>

<style>
    :root {
        --primary: #1a5c1e;
        --secondary: #f7c744;
    }

    .hero-overlay {
        background: linear-gradient(45deg, rgba(7, 74, 11, 0.9) 30%, rgba(247, 199, 68, 0.4) 100%);
    }

    .frame-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        perspective: 1000px;
    }

    .frame-card-inner {
        transition: transform 0.6s;
        transform-style: preserve-3d;
    }

    .frame-card:hover .frame-card-inner {
        transform: rotateY(5deg) rotateX(5deg) scale(1.02);
    }

    .availability-dot {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 0.8; }
        50% { opacity: 0.4; }
        100% { opacity: 0.8; }
    }

    .section-title {
        background: linear-gradient(45deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .from-black\/60 {
  --tw-gradient-from: rgba(11, 130, 46, 0.15) var(--tw-gradient-from-position);
  --tw-gradient-to: rgb(0 0 0 / 0) var(--tw-gradient-to-position);
  --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
}
</style>

<!-- Enhanced Hero Section -->
<div class="relative h-[70vh] overflow-hidden">
    <img src="assets/uploads/newonlineoptics.png" class="absolute inset-0 w-full h-full object-cover" alt="Hero background">
    <div class="hero-overlay absolute inset-0"></div>
    <div class="relative h-full flex items-center justify-center text-center px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-7xl font-extrabold text-white font-heading mb-6 drop-shadow-2xl">
            Dejene & Zenebech Foundation
            </h1>
            <div class="animate-bounce mt-8">
                <svg class="w-12 h-12 mx-auto text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </div>
        </div>
    </div>
</div>
<!-- Mission Section -->
<div class="relative bg-gray-50 py-15">
    <div class="absolute inset-0 opacity-10 bg-repeat" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMTAiIGN5PSIxMCIgcj0iMiIgZmlsbD0iIzFiNWIxZSIvPjwvc3ZnPg==')"></div>
    
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative bg-white rounded-2xl shadow-2xl p-8 md:p-12 transform -translate-y-24">
            <div class="absolute -top-6 left-1/2 -translate-x-1/2">
                <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            
            <h2 class="text-3xl font-heading font-bold text-center mb-8 section-title">
                Continuing a Legacy of Vision
            </h2>
            
            <div class="prose-lg text-gray-600 space-y-6 text-center max-w-5xl mx-auto">
                <p class="text-2xl font-medium">
                In loving memory of <b>Dejene Nigatu & Zenebech Adem</b>, pioneers of one of Ethiopia’s first eyeglass shop, Dejene Nigatu Optics, we continue their legacy of making vision care accessible to all.


                </p>
                <p class="text-xxl font-medium">
                Through the <b>Dejene & Zenebech</b> Foundation, we offer one free eyeglass frame per person to those in need. Simply choose a frame and call us to reserve it. Pickups are available every Friday at our office.

                </p>
                <p class="text-2xl font-medium">
                If you are a kind-hearted person who wants to help someone in need, you are welcome to pick up a frame on their behalf and make a difference in their life. <br>
                Unfortunately, we are unable to offer doctor-prescribed lenses for free. However, if you have a valid prescription and want to fit lenses into our free frames, we provide them at a <b>70% discount </b>off the actual price to make vision care more affordable.

                </p>
                <p class="text-3xl font-heading font-bold text-center mb-8">
                Choose. Call. Pick up. Give the gift of clear vision.
                </p>


                
                <div class="grid md:grid-cols-2 gap-8 mt-12">
                    <div class="p-6 bg-primary-50 rounded-xl ">
                        <h3 class="text-xl font-heading font-semibold text-primary mb-4 ">Our Promise</h3>
                        <p class="">Simply choose a frame and call us to reserve it. Pickups are available every <b>Friday at our office</b>.</p>
                    </div>
                    <div class="p-6 bg-secondary-50 rounded-xl">
                        <h3 class="text-xl font-heading font-semibold text-secondary-800 mb-4">Community Impact</h3>
                        <p> This initiative reflects the generosity and compassion Zenebech and Dejene stood for, ensuring that more people can see the world clearly.
                            </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page" style="padding-bottom: 0px !important;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">


                    <div class="gal-container">

                        <?php
                        /* ===================== Pagination Code Starts ================== */
                        $adjacents = 5;

                        $statement = $pdo->prepare("SELECT * FROM tbl_photo ORDER BY id DESC");
                        $statement->execute();
                        $total_pages = $statement->rowCount();


                        $targetpage = $_SERVER['PHP_SELF'];   //your file name  (the name of this file)
                        $limit = 12;                                 //how many items to show per page
                        $page = @$_GET['page'];
                        if ($page)
                            $start = ($page - 1) * $limit;          //first item to display on this page
                        else
                            $start = 0;

                        $statement = $pdo->prepare("SELECT * FROM tbl_photo ORDER BY id DESC LIMIT $start, $limit");
                        $statement->execute();
                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);


                        if ($page == 0) $page = 1;                  //if no page var is given, default to 1.
                        $prev = $page - 1;                          //previous page is page - 1
                        $next = $page + 1;                          //next page is page + 1
                        $lastpage = ceil($total_pages / $limit);      //lastpage is = total pages / items per page, rounded up.
                        $lpm1 = $lastpage - 1;
                        $pagination = "";
                        if ($lastpage > 1) {
                            $pagination .= "<div class=\"pagination\">";
                            if ($page > 1)
                                $pagination .= "<a href=\"$targetpage?page=$prev\">&#171; previous</a>";
                            else
                                $pagination .= "<span class=\"disabled\">&#171; previous</span>";
                            if ($lastpage < 7 + ($adjacents * 2))   //not enough pages to bother breaking it up
                            {
                                for ($counter = 1; $counter <= $lastpage; $counter++) {
                                    if ($counter == $page)
                                        $pagination .= "<span class=\"current\">$counter</span>";
                                    else
                                        $pagination .= "<a href=\"$targetpage?page=$counter\">$counter</a>";
                                }
                            } elseif ($lastpage > 5 + ($adjacents * 2))    //enough pages to hide some
                            {
                                if ($page < 1 + ($adjacents * 2)) {
                                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                                        if ($counter == $page)
                                            $pagination .= "<span class=\"current\">$counter</span>";
                                        else
                                            $pagination .= "<a href=\"$targetpage?page=$counter\">$counter</a>";
                                    }
                                    $pagination .= "...";
                                    $pagination .= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
                                    $pagination .= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";
                                } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                                    $pagination .= "<a href=\"$targetpage?page=1\">1</a>";
                                    $pagination .= "<a href=\"$targetpage?page=2\">2</a>";
                                    $pagination .= "...";
                                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                                        if ($counter == $page)
                                            $pagination .= "<span class=\"current\">$counter</span>";
                                        else
                                            $pagination .= "<a href=\"$targetpage?page=$counter\">$counter</a>";
                                    }
                                    $pagination .= "...";
                                    $pagination .= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
                                    $pagination .= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";
                                } else {
                                    $pagination .= "<a href=\"$targetpage?page=1\">1</a>";
                                    $pagination .= "<a href=\"$targetpage?page=2\">2</a>";
                                    $pagination .= "...";
                                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                                        if ($counter == $page)
                                            $pagination .= "<span class=\"current\">$counter</span>";
                                        else
                                            $pagination .= "<a href=\"$targetpage?page=$counter\">$counter</a>";
                                    }
                                }
                            }
                            if ($page < $counter - 1)
                                $pagination .= "<a href=\"$targetpage?page=$next\">next &#187;</a>";
                            else
                                $pagination .= "<span class=\"disabled\">next &#187;</span>";
                            $pagination .= "</div>\n";
                        }
                        /* ===================== Pagination Code Ends ================== */
                        ?>
                     <!-- Frame Gallery Section -->
<div class=" mx-auto  sm:px-7  lg:px-8 py-16" style="max-width: 120rem;">
    <h2 class="text-4xl font-heading font-bold text-center mb-12 section-title">
        Available Frames
    </h2>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12">
        <?php foreach ($result as $row): ?>
        <div class="frame-card group">
            <div class="frame-card-inner relative rounded-xl overflow-hidden shadow-lg hover:shadow-2xl">
                <button type="button" 
                    data-modal-target="frame-modal"
                    data-modal-toggle="frame-modal"
                    class="block w-full"
                    data-id="<?= $row['id'] ?>"
                    data-image="assets/uploads/<?= $row['photo'] ?>"
                    data-caption="<?= $row['caption'] ?>">
                    
                    <div class="relative aspect-square overflow-hidden">
                        <img src="assets/uploads/<?= $row['photo'] ?>" 
                             alt="<?= $row['caption'] ?>"
                             class="w-full h-full object-cover transform transition duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                            <h3 class="text-lg font-semibold"><?= $row['caption'] ?></h3>
                            <div class="flex items-center mt-2">
                                <span class="availability-dot w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                <span class="text-sm">Available Now</span>
                            </div>
                        </div>
                    </div>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Enhanced Pagination -->
    <div class="flex justify-center mt-12">
        <?= $pagination ?>
    </div>
</div>

                        </div>


                 
                    </div>

                    <div class="pagination">
                        <?php
                        echo $pagination;
                        ?>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
<!-- CTA Section -->
<div class="bg-primary py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 relative overflow-hidden">
            <div class="absolute -top-16 -right-16 w-32 h-32 bg-secondary rounded-full opacity-20"></div>
            <div class="absolute -bottom-16 -left-16 w-32 h-32 bg-secondary rounded-full opacity-20"></div>
            
            <h2 class="text-3xl font-heading font-bold text-gray-900 mb-6">
                Ready to Make a Difference?
            </h2>
            
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                Claim your free frame or help someone in need today. Together, we're changing lives through clearer vision.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#" class="inline-flex items-center px-8 py-4 bg-primary text-white font-semibold rounded-full hover:bg-primary-800 transition-all transform hover:-translate-y-1">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    Reserve Your Frame
                </a>
                
                <a href="#" class="inline-flex items-center px-8 py-4 border-2 border-primary text-primary font-semibold rounded-full hover:bg-primary-50 transition-all transform hover:-translate-y-1">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Visit Our Location
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Frame Modal -->
<!-- Frame Modal -->
<div id="frame-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="modal-title"></h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="frame-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6">
                <img id="modal-image" src="" class="w-full h-64 object-contain mb-4" alt="Frame detail">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-bold text-gray-900">Details</h4>
                        <ul class="mt-2 text-gray-600" id="modal-details"></ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">Availability</h4>
                        <div class="mt-2 flex items-center">
                            <div class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></div>
                            <span class="text-sm">In Stock</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                <!-- <button data-modal-hide="frame-modal" type="button"
                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                    id="reserve-button">
                    Reserve Now
                </button> -->
                <button data-modal-hide="frame-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<?php require_once('footer.php'); ?>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        "50": "#faf5ff",
                        "100": "#f3e8ff",
                        "200": "#e9d5ff",
                        "300": "#d8b4fe",
                        "400": "#c084fc",
                        "500": "#a855f7",
                        "600": "#9333ea",
                        "700": "#7e22ce",
                        "800": "#6b21a8",
                        "900": "#581c87"
                    },
                    secondary: {
                        "50": "#fffbeb",
                        "100": "#fef3c7",
                        "200": "#fde68a",
                        "300": "#fcd34d",
                        "400": "#fbbf24",
                        "500": "#f59e0b",
                        "600": "#d97706",
                        "700": "#b45309",
                        "800": "#92400e",
                        "900": "#78350f"
                    }
                },
                fontFamily: {
                    heading: ['Exo 2', 'sans-serif'],
                    body: ['Poppins', 'sans-serif']
                }
            }
        }
    }
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle modal show event
        document.querySelectorAll('[data-modal-target="frame-modal"]').forEach(button => {
            button.addEventListener('click', function() {
                const frameId = this.dataset.id;
                const frameImage = this.dataset.image;
                const frameCaption = this.dataset.caption;

                // Update modal content
                document.getElementById('modal-title').textContent = frameCaption;
                document.getElementById('modal-image').src = frameImage;

                // Update reserve button with frame ID
                const reserveButton = document.getElementById('reserve-button');
                reserveButton.dataset.reserveId = frameId;
                reserveButton.onclick = function() {
                    window.location.href = `reserve.php?frame_id=${frameId}`;
                };

                // If you have more details from the database:
                // You would need to fetch additional details via AJAX here
                // or include them in data attributes on the button
            });
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>