<?php
/**
 * Universal Admin API for Content Management
 * Handles saving and loading of content for all pages
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$contentDir = __DIR__ . '/content';

// Ensure content directory exists
if (!is_dir($contentDir)) {
    mkdir($contentDir, 0755, true);
}

// GET request - Load content for a specific page
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $page = isset($_GET['page']) ? basename($_GET['page']) : 'our-mission';
    $contentFile = $contentDir . '/' . $page . '.json';
    
    if (file_exists($contentFile)) {
        $content = file_get_contents($contentFile);
        echo $content;
    } else {
        // Return default content structure based on page
        $defaultContent = getDefaultContent($page);
        echo json_encode($defaultContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    exit();
}

// POST request - Save content for a specific page
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if ($data === null || !isset($data['page'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid JSON data or missing page identifier']);
        exit();
    }
    
    $page = basename($data['page']);
    unset($data['page']); // Remove page from data before saving
    
    // Sanitize data
    $sanitized = [];
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $sanitized[$key] = array_map(function($item) {
                return is_string($item) ? htmlspecialchars(trim($item), ENT_QUOTES, 'UTF-8') : $item;
            }, $value);
        } else {
            $sanitized[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }
    }
    
    // Save to file
    $contentFile = $contentDir . '/' . $page . '.json';
    $jsonContent = json_encode($sanitized, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    if (file_put_contents($contentFile, $jsonContent) !== false) {
        echo json_encode(['success' => true, 'message' => 'Content saved successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to save content']);
    }
    exit();
}

// Get list of available pages
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['list'])) {
    $pages = [
        'our-mission' => 'Our Mission',
        'our-vision' => 'Our Vision',
        'our-goal' => 'Our Goal',
        'overview' => 'Overview',
        'civil-work' => 'Civil Work',
        'transportation' => 'Transportation',
        'manpower-supply' => 'Manpower Supply',
        'material-supply' => 'Material Supply',
        'porta-cabin-supply' => 'Porta Cabin Supply',
        'our-services' => 'Our Services',
        'mobile-cranes' => 'Mobile Cranes',
        'heavy-equipment' => 'Heavy Equipment',
        'light-equipment' => 'Light Equipment',
        'earth-moving-equipment' => 'Earth Moving Equipment',
        'industrial-equipment' => 'Industrial Equipment',
        'heavy-&-light-vehicles' => 'Heavy and Light Vehicles',
        'company-profile' => 'Company Profile',
        'site-info' => 'Site Information (Header Contact Info)',
        'index' => 'Home Page',
        'contact-us' => 'Contact Us'
    ];
    echo json_encode($pages);
    exit();
}

// Get default content structure for a page
function getDefaultContent($page) {
    $defaults = [
        'our-mission' => [
            'pageSubtitle' => 'Focused on service excellence',
            'pageTitle' => 'Our Mission',
            'missionStatement' => 'To provide professional services that consistently meet our clients\' expectations through safety, quality and on‑time delivery — honoring commitments and being a partner they can rely on across Saudi Arabia.',
            'howWeDeliverTitle' => 'How We Deliver',
            'howWeDeliverPara1' => 'Our mission is to deliver safe, high‑quality and on‑schedule work by integrating disciplined planning, skilled manpower and a well‑maintained fleet — enabling fast mobilization and predictable outcomes.',
            'howWeDeliverPara2' => 'We communicate clearly, supervise rigorously and resolve issues early so our clients experience a smooth, reliable project from start to finish.',
            'benefit1' => 'Service excellence and client focus',
            'benefit2' => 'Quality workmanship, verified and documented',
            'benefit3' => 'On‑time delivery with transparent reporting',
            'chairmanMessage' => 'Al Tameez Al Thaqib began with a dream and has stood the test of time. By putting people and customers at the center, adopting world‑class technology and simple, reliable processes, we deliver consistent results. Our commitment to quality, cost‑competitiveness and continuous improvement has earned the trust of our clients. We do not just create the best solutions — we build lasting relationships.',
            'chairmanMessageLabel' => 'Chairman\'s Message'
        ],
        'our-vision' => [
            'pageSubtitle' => 'Building the future',
            'pageTitle' => 'Our Vision',
            'visionStatement' => 'To be the most trusted and respected contracting partner in Saudi Arabia, recognized for excellence in safety, quality and innovation.',
            'sectionTitle' => 'Our Vision',
            'sectionPara1' => 'We envision a future where Al Tameez Al Thaqib is the first choice for clients seeking reliable, high-quality construction and industrial services.',
            'sectionPara2' => 'Through continuous improvement, strategic partnerships and investment in our people and technology, we will lead the industry in delivering exceptional value.'
        ],
        'our-goal' => [
            'pageSubtitle' => 'Driven by outcomes',
            'pageTitle' => 'Our Goal',
            'goalStatement' => 'To deliver superior quality in the shortest practical timeframe while maximizing value for our clients — through safe operations, disciplined planning and dependable execution across Saudi Arabia.',
            'sectionTitle' => 'Our Goals',
            'sectionPara1' => 'We are committed to achieving excellence in every project through strategic planning and flawless execution.',
            'sectionPara2' => 'Our goals focus on safety, quality, timely delivery and client satisfaction.'
        ],
        'overview' => [
            'pageSubtitle' => 'About Al Tameez Al Thaqib',
            'pageTitle' => 'Overview',
            'overviewText' => 'We are a Saudi-based engineering and contracting company providing end‑to‑end solutions across civil works, heavy equipment rentals & industrial services, transportation, manpower and material supply. Our teams deliver reliable results for public and private sector projects throughout the Kingdom.'
        ],
        'civil-work' => [
            'pageSubtitle' => 'Expert civil construction services',
            'pageTitle' => 'Civil Work',
            'description' => 'We provide comprehensive civil construction services including site preparation, earthworks, road construction, infrastructure development, and building construction. Our experienced team delivers high-quality projects on time and within budget.',
            'sectionTitle' => 'Our Civil Work Services',
            'sectionContent' => 'Our civil work division specializes in a wide range of construction activities. We handle projects from initial site preparation through final completion, ensuring quality, safety, and adherence to all regulatory requirements. Our capabilities include earthworks, excavation, grading, paving, drainage systems, and complete infrastructure development for residential, commercial, and industrial projects across Saudi Arabia.'
        ],
        'transportation' => [
            'pageSubtitle' => 'Reliable transportation solutions',
            'pageTitle' => 'Transportation',
            'description' => 'We offer comprehensive transportation services for construction materials, equipment, and personnel. Our modern fleet and experienced drivers ensure safe and timely delivery to project sites throughout the Kingdom.',
            'sectionTitle' => 'Transportation Services',
            'sectionContent' => 'Our transportation division provides reliable and efficient logistics solutions for the construction industry. We operate a diverse fleet of vehicles including flatbed trucks, dump trucks, low loaders, and specialized transport equipment. Our services cover material transport, equipment delivery, personnel transportation, and project logistics management. With extensive experience across Saudi Arabia, we ensure timely delivery and safe handling of all cargo.'
        ],
        'manpower-supply' => [
            'pageSubtitle' => 'Skilled workforce solutions',
            'pageTitle' => 'Manpower Supply',
            'description' => 'We provide skilled and semi-skilled manpower for construction projects across various trades. Our workforce includes carpenters, masons, electricians, plumbers, welders, and general laborers, all trained and certified for their respective roles.',
            'sectionTitle' => 'Manpower Supply Services',
            'sectionContent' => 'Our manpower supply division connects qualified workers with construction projects throughout Saudi Arabia. We maintain a database of skilled professionals across all construction trades, ensuring that clients have access to the right talent when needed. All our workers are properly trained, certified, and comply with local labor regulations. We handle recruitment, screening, documentation, and deployment, making it easy for clients to scale their workforce up or down based on project requirements.'
        ],
        'material-supply' => [
            'pageSubtitle' => 'Quality construction materials',
            'pageTitle' => 'Material Supply',
            'description' => 'We supply high-quality construction materials including cement, steel, aggregates, bricks, tiles, and other building materials. Our extensive network of suppliers ensures competitive pricing and reliable availability.',
            'sectionTitle' => 'Material Supply Services',
            'sectionContent' => 'Our material supply division serves as a one-stop solution for all construction material needs. We maintain strong relationships with leading manufacturers and suppliers, enabling us to source quality materials at competitive prices. Our inventory includes cement, steel reinforcement, aggregates, ready-mix concrete, bricks, blocks, tiles, pipes, electrical and plumbing materials, and other essential construction supplies. We ensure timely delivery to project sites and maintain quality standards throughout the supply chain.'
        ],
        'porta-cabin-supply' => [
            'pageSubtitle' => 'Portable cabin solutions',
            'pageTitle' => 'Porta Cabin Supply',
            'description' => 'We provide portable cabin solutions for construction sites, offices, storage, and temporary accommodation. Our cabins are available in various sizes and configurations to meet different project needs.',
            'sectionTitle' => 'Porta Cabin Supply Services',
            'sectionContent' => 'Our porta cabin supply division offers flexible and cost-effective temporary structure solutions for construction sites and other applications. We provide a wide range of portable cabins including site offices, accommodation units, storage facilities, restrooms, and custom configurations. All our cabins are built to high standards, fully equipped with necessary amenities, and can be quickly deployed and relocated as needed. We handle delivery, installation, maintenance, and removal, providing a complete turnkey solution for temporary space requirements.'
        ],
        'our-services' => [
            'pageSubtitle' => 'Comprehensive service offerings',
            'pageTitle' => 'Our Services',
            'description' => 'We offer a comprehensive range of construction and industrial services including civil works, transportation, manpower supply, material supply, and portable cabin solutions. Our integrated approach ensures seamless project execution.',
            'sectionTitle' => 'Complete Service Portfolio',
            'sectionContent' => 'Al Tameez Al Thaqib provides end-to-end solutions for construction and industrial projects across Saudi Arabia. Our service portfolio includes civil construction, transportation and logistics, skilled manpower supply, construction material supply, and portable cabin solutions. By offering these integrated services, we enable clients to work with a single trusted partner for all their project needs. Our experienced teams, modern equipment, and commitment to quality ensure successful project delivery from start to finish.'
        ],
        'site-info' => [
            'phone' => '+966 011 2068132',
            'email' => 'info@tmz-c.com',
            'addressLine1' => 'Abi Jafar Al Mansur, Al Yarmuk',
            'addressLine2' => 'Riyadh, Kingdom of Saudi Arabia',
            'workingHours' => '8.00 Am - 17.00 Pm',
            'callUsLabel' => 'Call Us 24/7',
            'sendMailLabel' => 'send mail us'
        ],
        'mobile-cranes' => [
            'pageSubtitle' => 'Certified operators and modern fleet',
            'pageTitle' => 'Mobile Cranes',
            'description' => 'Certified operators and a modern fleet for safe, efficient lifting. Rough terrain, all-terrain, truck-mounted, city and crawler cranes available across Saudi Arabia.',
            'sectionTitle' => 'Service Overview',
            'sectionContent' => 'Al Tameez Al Thaqib Contracting Company provides a comprehensive range of mobile crane solutions operated by certified professionals. Our cranes are maintained to the highest standards to ensure safe and efficient lifting operations for construction, infrastructure, industrial shutdowns, and logistics projects across Saudi Arabia. We recommend the right crane capacity and configuration for each lift plan, taking into account site conditions, rigging, access, and safety compliance. Short-term, long-term, and project-based rentals are available with 24/7 support.'
        ],
        'heavy-equipment' => [
            'pageSubtitle' => 'Reliable heavy equipment rentals',
            'pageTitle' => 'Heavy Equipment',
            'description' => 'Reliable heavy equipment rentals with certified operators and rapid mobilization. Boom trucks, forklifts, tele handlers, reach stackers, manlifts, and more.',
            'sectionTitle' => 'Service Overview',
            'sectionContent' => 'Al Tameez Al Thaqib Contracting Company supplies a wide range of heavy equipment with qualified operators and preventive maintenance support. We mobilize quickly and ensure safe, productive operations for construction, logistics, and industrial sites across Saudi Arabia. Rentals are available daily, weekly, or monthly with flexible deployment, on-site support, and HSE-compliant procedures.'
        ],
        'light-equipment' => [
            'pageSubtitle' => 'Compact and efficient solutions',
            'pageTitle' => 'Light Equipment',
            'description' => 'Compact and efficient light equipment for power, air, and lighting. Available with quick delivery and on-site support across Saudi Arabia.',
            'sectionTitle' => 'Service Overview',
            'sectionContent' => 'We provide dependable light equipment to power your site, support fabrication, and ensure safe nighttime operations. All units are maintained, load-tested, and ready for immediate deployment. Flexible rentals and maintenance support are included to minimize downtime.'
        ],
        'earth-moving-equipment' => [
            'pageSubtitle' => 'Powerful earth moving solutions',
            'pageTitle' => 'Earth Moving Equipment',
            'description' => 'Comprehensive earth moving equipment for excavation, grading, and site preparation. Excavators, bulldozers, loaders, and compactors available with experienced operators.',
            'sectionTitle' => 'Service Overview',
            'sectionContent' => 'Our earth moving equipment division provides powerful machinery for construction and infrastructure projects. We offer excavators, bulldozers, wheel loaders, backhoe loaders, compactors, and graders, all operated by certified professionals. Our equipment is well-maintained and ready for immediate deployment to project sites across Saudi Arabia.'
        ],
        'industrial-equipment' => [
            'pageSubtitle' => 'Industrial machinery solutions',
            'pageTitle' => 'Industrial Equipment',
            'description' => 'Specialized industrial equipment for manufacturing, processing, and maintenance operations. Professional service and reliable equipment for industrial applications.',
            'sectionTitle' => 'Service Overview',
            'sectionContent' => 'We provide specialized industrial equipment for manufacturing, processing, and maintenance operations across various industries. Our fleet includes material handling equipment, processing machinery, and specialized tools required for industrial applications. All equipment is maintained to industrial standards and operated by trained professionals.'
        ],
        'heavy-&-light-vehicles' => [
            'pageSubtitle' => 'Vehicle fleet solutions',
            'pageTitle' => 'Heavy and Light Vehicles',
            'description' => 'Comprehensive vehicle fleet including heavy trucks, light commercial vehicles, and specialized transport vehicles for construction and logistics needs.',
            'sectionTitle' => 'Service Overview',
            'sectionContent' => 'Our vehicle fleet division provides a wide range of transportation solutions for construction and logistics operations. We offer heavy trucks, dump trucks, flatbed trucks, light commercial vehicles, and specialized transport vehicles. All vehicles are well-maintained, insured, and operated by licensed drivers, ensuring safe and reliable transportation services across Saudi Arabia.'
        ],
        'company-profile' => [
            'pageSubtitle' => 'About Al Tameez Al Thaqib',
            'pageTitle' => 'Company Profile',
            'description' => 'Al Tameez Al Thaqib is a leading contracting company in Saudi Arabia, providing comprehensive construction and industrial services with a commitment to quality and excellence.',
            'sectionTitle' => 'Company Overview',
            'sectionContent' => 'Al Tameez Al Thaqib Contracting Company is a trusted name in the construction and industrial services sector in Saudi Arabia. With years of experience and a dedicated team of professionals, we deliver high-quality solutions across civil works, equipment rentals, transportation, manpower supply, and material supply. Our commitment to safety, quality, and timely delivery has earned us the trust of clients across the Kingdom.'
        ],
        'index' => [
            'aboutSubtitle' => 'Al Tameez Al Thaqib since 1996',
            'aboutTitle' => 'Building Your Visions. Creating Reality.',
            'aboutDescription' => 'We build multi-family and affordable housing communities, industrial facilities, public and private healthcare facilities, fitness centers and office buildings. We improve the supply chain management process, increase operational efficiencies and build environments that foster creativity. Our commitment to sustainable construction helps improve the communities in which we build.',
            'aboutDescription2' => 'The structures we create provide our clients with more than just buildings – we deliver environments that help them achieve their goals.',
            'whyChooseUsSubtitle' => 'We work with integrity',
            'whyChooseUsTitle' => 'Why Choose Us?',
            'whyChooseUsDescription' => 'Developed in close collaboration with our partners and clients, combines industry knowledge, decades of experience, ingenuity and adaptability to deliver excellence to our clients.',
            'servicesSubtitle' => 'OUR SERVICES',
            'servicesTitle' => 'Explore Our Service Solutions',
            'servicesDescription' => 'Al Tameez Al Thaqib provides comprehensive construction and industrial services across Saudi Arabia. From civil works to equipment rentals, we deliver reliable solutions with safety, quality and on-time delivery.'
        ],
        'contact-us' => [
            'pageSubtitle' => 'We support you 24/7',
            'pageTitle' => 'Contact Us',
            'description' => 'At Al Tameez Engineering & Construction, we\'re committed to delivering excellence in every project. Contact us today to discuss your construction needs and let us bring your vision to life.',
            'getInTouchSubtitle' => 'let\'s work together',
            'getInTouchTitle' => 'Get In Touch With Us',
            'getInTouchDescription' => 'If you need to repair or replace your Plumbing system, call today and talk to one of our Plumbing specialists.',
            'locationsTitle' => 'Our Locations',
            'locationsDescription' => 'With a presence in three countries. We has the most comprehensive local office network in the industry.'
        ]
    ];
    
    return isset($defaults[$page]) ? $defaults[$page] : [];
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
?>
