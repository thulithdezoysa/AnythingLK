<?php
require_once CORE . '/Controller.php';
require_once APP  . '/models/ProductModel.php';
require_once APP  . '/models/TrackingModel.php';
require_once APP  . '/models/HomeSectionModel.php';

class HomeController extends Controller {
    public function index(array $params = []): void {
        $pm          = new ProductModel();
        $featured    = $pm->getFeatured(8);
        $newArrivals = $pm->getNew(8);
        $bestSellers = $pm->getBestSellers(8);
        $categories  = Helper::rootCategories();
        $now          = date('Y-m-d H:i:s');
        $banners      = $this->db->select("SELECT * FROM banners WHERE status=1 AND position='hero' AND (start_date IS NULL OR start_date<='$now') AND (end_date IS NULL OR end_date>='$now') ORDER BY sort_order") ?: [];
        $midBanners   = $this->db->select("SELECT * FROM banners WHERE status=1 AND position='home_mid' AND (start_date IS NULL OR start_date<='$now') AND (end_date IS NULL OR end_date>='$now') ORDER BY sort_order") ?: [];
        $promoBanners = $this->db->select("SELECT * FROM banners WHERE status=1 AND position='home_promo' AND (start_date IS NULL OR start_date<='$now') AND (end_date IS NULL OR end_date>='$now') ORDER BY sort_order") ?: [];
        $personalised = (new TrackingModel())->getPersonalised(Auth::id(), session_id(), 8);

        // Dynamic homepage sections
        $hsm              = new HomeSectionModel();
        $homeHeroSections = $hsm->getHeroSections();
        $homeCollections  = $hsm->getCollections();
        $homeReviews      = $hsm->getReviews();

        // Products for each hero section (by selected category)
        $homeHeroProducts = [];
        foreach ($homeHeroSections as $hs) {
            if (!empty($hs['category_id'])) {
                $homeHeroProducts[(int)$hs['id']] = $hsm->getProductsByCategory((int)$hs['category_id'], 12);
            }
        }

        $this->view('home/index', compact(
            'featured','newArrivals','bestSellers','categories',
            'banners','midBanners','promoBanners','personalised',
            'homeHeroSections','homeCollections','homeReviews','homeHeroProducts'
        ));
    }
}
