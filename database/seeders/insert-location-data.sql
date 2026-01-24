-- =====================================================
-- FarmVax Location Data Insert
-- Countries: Nigeria & Liberia with States and LGAs
-- =====================================================

-- Clear existing data (optional - remove if you want to keep existing data)
-- DELETE FROM lgas;
-- DELETE FROM states;
-- DELETE FROM countries;

-- =====================================================
-- INSERT COUNTRIES
-- =====================================================

INSERT INTO countries (id, name, code, iso2, iso3, phone_code, region, subregion, created_at, updated_at) VALUES
(1, 'Nigeria', 'NG', 'NG', 'NGA', '+234', 'Africa', 'Western Africa', NOW(), NOW()),
(2, 'Liberia', 'LR', 'LR', 'LBR', '+231', 'Africa', 'Western Africa', NOW(), NOW());

-- =====================================================
-- INSERT NIGERIAN STATES
-- =====================================================

INSERT INTO states (id, country_id, name, code, latitude, longitude, created_at, updated_at) VALUES
-- Nigerian States (37 including FCT)
(1, 1, 'Abia', 'AB', 5.4527, 7.5248, NOW(), NOW()),
(2, 1, 'Adamawa', 'AD', 9.3265, 12.3984, NOW(), NOW()),
(3, 1, 'Akwa Ibom', 'AK', 5.0077, 7.8336, NOW(), NOW()),
(4, 1, 'Anambra', 'AN', 6.2209, 6.9370, NOW(), NOW()),
(5, 1, 'Bauchi', 'BA', 10.3158, 9.8442, NOW(), NOW()),
(6, 1, 'Bayelsa', 'BY', 4.7719, 6.0699, NOW(), NOW()),
(7, 1, 'Benue', 'BE', 7.3362, 8.7407, NOW(), NOW()),
(8, 1, 'Borno', 'BO', 11.8333, 13.1500, NOW(), NOW()),
(9, 1, 'Cross River', 'CR', 5.8738, 8.5989, NOW(), NOW()),
(10, 1, 'Delta', 'DE', 5.6805, 5.9239, NOW(), NOW()),
(11, 1, 'Ebonyi', 'EB', 6.2649, 8.0137, NOW(), NOW()),
(12, 1, 'Edo', 'ED', 6.6346, 5.9343, NOW(), NOW()),
(13, 1, 'Ekiti', 'EK', 7.7190, 5.3110, NOW(), NOW()),
(14, 1, 'Enugu', 'EN', 6.5244, 7.5105, NOW(), NOW()),
(15, 1, 'FCT - Abuja', 'FC', 9.0579, 7.4951, NOW(), NOW()),
(16, 1, 'Gombe', 'GO', 10.2904, 11.1672, NOW(), NOW()),
(17, 1, 'Imo', 'IM', 5.5720, 7.0588, NOW(), NOW()),
(18, 1, 'Jigawa', 'JI', 12.2289, 9.5616, NOW(), NOW()),
(19, 1, 'Kaduna', 'KD', 10.5222, 7.4383, NOW(), NOW()),
(20, 1, 'Kano', 'KN', 12.0022, 8.5919, NOW(), NOW()),
(21, 1, 'Katsina', 'KT', 12.9908, 7.6177, NOW(), NOW()),
(22, 1, 'Kebbi', 'KE', 11.4963, 4.1994, NOW(), NOW()),
(23, 1, 'Kogi', 'KO', 7.7333, 6.7333, NOW(), NOW()),
(24, 1, 'Kwara', 'KW', 8.9670, 4.3789, NOW(), NOW()),
(25, 1, 'Lagos', 'LA', 6.5244, 3.3792, NOW(), NOW()),
(26, 1, 'Nasarawa', 'NA', 8.4979, 8.4955, NOW(), NOW()),
(27, 1, 'Niger', 'NI', 9.9319, 5.5978, NOW(), NOW()),
(28, 1, 'Ogun', 'OG', 6.9978, 3.4717, NOW(), NOW()),
(29, 1, 'Ondo', 'ON', 6.9150, 5.1950, NOW(), NOW()),
(30, 1, 'Osun', 'OS', 7.5629, 4.5200, NOW(), NOW()),
(31, 1, 'Oyo', 'OY', 8.1555, 3.6173, NOW(), NOW()),
(32, 1, 'Plateau', 'PL', 9.2182, 9.5179, NOW(), NOW()),
(33, 1, 'Rivers', 'RI', 4.8156, 6.9778, NOW(), NOW()),
(34, 1, 'Sokoto', 'SO', 13.0622, 5.2339, NOW(), NOW()),
(35, 1, 'Taraba', 'TA', 7.9999, 10.7668, NOW(), NOW()),
(36, 1, 'Yobe', 'YO', 12.2939, 11.4416, NOW(), NOW()),
(37, 1, 'Zamfara', 'ZA', 12.1203, 6.2336, NOW(), NOW());

-- =====================================================
-- INSERT LIBERIAN COUNTIES (States)
-- =====================================================

INSERT INTO states (id, country_id, name, code, latitude, longitude, created_at, updated_at) VALUES
(38, 2, 'Bomi', 'BM', 6.7561, -10.8452, NOW(), NOW()),
(39, 2, 'Bong', 'BG', 6.8294, -9.3672, NOW(), NOW()),
(40, 2, 'Gbarpolu', 'GP', 7.4951, -10.0807, NOW(), NOW()),
(41, 2, 'Grand Bassa', 'GB', 6.2308, -9.8122, NOW(), NOW()),
(42, 2, 'Grand Cape Mount', 'CM', 7.0469, -11.0711, NOW(), NOW()),
(43, 2, 'Grand Gedeh', 'GG', 5.9222, -8.2211, NOW(), NOW()),
(44, 2, 'Grand Kru', 'GK', 4.7614, -8.2211, NOW(), NOW()),
(45, 2, 'Lofa', 'LO', 8.1911, -9.7233, NOW(), NOW()),
(46, 2, 'Margibi', 'MG', 6.5156, -10.3047, NOW(), NOW()),
(47, 2, 'Maryland', 'MY', 4.7272, -7.7319, NOW(), NOW()),
(48, 2, 'Montserrado', 'MO', 6.5507, -10.7605, NOW(), NOW()),
(49, 2, 'Nimba', 'NI', 7.6166, -8.4200, NOW(), NOW()),
(50, 2, 'River Cess', 'RC', 5.9025, -9.4569, NOW(), NOW()),
(51, 2, 'River Gee', 'RG', 5.2604, -7.8722, NOW(), NOW()),
(52, 2, 'Sinoe', 'SI', 5.4986, -8.6603, NOW(), NOW());

-- =====================================================
-- INSERT NIGERIAN LGAs (Local Government Areas)
-- =====================================================

-- LAGOS STATE LGAs (25 state_id = 25)
INSERT INTO lgas (state_id, name, created_at, updated_at) VALUES
(25, 'Agege', NOW(), NOW()),
(25, 'Ajeromi-Ifelodun', NOW(), NOW()),
(25, 'Alimosho', NOW(), NOW()),
(25, 'Amuwo-Odofin', NOW(), NOW()),
(25, 'Apapa', NOW(), NOW()),
(25, 'Badagry', NOW(), NOW()),
(25, 'Epe', NOW(), NOW()),
(25, 'Eti-Osa', NOW(), NOW()),
(25, 'Ibeju-Lekki', NOW(), NOW()),
(25, 'Ifako-Ijaiye', NOW(), NOW()),
(25, 'Ikeja', NOW(), NOW()),
(25, 'Ikorodu', NOW(), NOW()),
(25, 'Kosofe', NOW(), NOW()),
(25, 'Lagos Island', NOW(), NOW()),
(25, 'Lagos Mainland', NOW(), NOW()),
(25, 'Mushin', NOW(), NOW()),
(25, 'Ojo', NOW(), NOW()),
(25, 'Oshodi-Isolo', NOW(), NOW()),
(25, 'Shomolu', NOW(), NOW()),
(25, 'Surulere', NOW(), NOW());

-- KANO STATE LGAs (state_id = 20)
INSERT INTO lgas (state_id, name, created_at, updated_at) VALUES
(20, 'Ajingi', NOW(), NOW()),
(20, 'Albasu', NOW(), NOW()),
(20, 'Bagwai', NOW(), NOW()),
(20, 'Bebeji', NOW(), NOW()),
(20, 'Bichi', NOW(), NOW()),
(20, 'Bunkure', NOW(), NOW()),
(20, 'Dala', NOW(), NOW()),
(20, 'Dambatta', NOW(), NOW()),
(20, 'Dawakin Kudu', NOW(), NOW()),
(20, 'Dawakin Tofa', NOW(), NOW()),
(20, 'Doguwa', NOW(), NOW()),
(20, 'Fagge', NOW(), NOW()),
(20, 'Gabasawa', NOW(), NOW()),
(20, 'Garko', NOW(), NOW()),
(20, 'Garun Mallam', NOW(), NOW()),
(20, 'Gaya', NOW(), NOW()),
(20, 'Gezawa', NOW(), NOW()),
(20, 'Gwale', NOW(), NOW()),
(20, 'Gwarzo', NOW(), NOW()),
(20, 'Kabo', NOW(), NOW()),
(20, 'Kano Municipal', NOW(), NOW()),
(20, 'Karaye', NOW(), NOW()),
(20, 'Kibiya', NOW(), NOW()),
(20, 'Kiru', NOW(), NOW()),
(20, 'Kumbotso', NOW(), NOW()),
(20, 'Kunchi', NOW(), NOW()),
(20, 'Kura', NOW(), NOW()),
(20, 'Madobi', NOW(), NOW()),
(20, 'Makoda', NOW(), NOW()),
(20, 'Minjibir', NOW(), NOW()),
(20, 'Nasarawa', NOW(), NOW()),
(20, 'Rano', NOW(), NOW()),
(20, 'Rimin Gado', NOW(), NOW()),
(20, 'Rogo', NOW(), NOW()),
(20, 'Shanono', NOW(), NOW()),
(20, 'Sumaila', NOW(), NOW()),
(20, 'Takai', NOW(), NOW()),
(20, 'Tarauni', NOW(), NOW()),
(20, 'Tofa', NOW(), NOW()),
(20, 'Tsanyawa', NOW(), NOW()),
(20, 'Tudun Wada', NOW(), NOW()),
(20, 'Ungogo', NOW(), NOW()),
(20, 'Warawa', NOW(), NOW()),
(20, 'Wudil', NOW(), NOW());

-- KADUNA STATE LGAs (state_id = 19)
INSERT INTO lgas (state_id, name, created_at, updated_at) VALUES
(19, 'Birnin Gwari', NOW(), NOW()),
(19, 'Chikun', NOW(), NOW()),
(19, 'Giwa', NOW(), NOW()),
(19, 'Igabi', NOW(), NOW()),
(19, 'Ikara', NOW(), NOW()),
(19, 'Jaba', NOW(), NOW()),
(19, 'Jema''a', NOW(), NOW()),
(19, 'Kachia', NOW(), NOW()),
(19, 'Kaduna North', NOW(), NOW()),
(19, 'Kaduna South', NOW(), NOW()),
(19, 'Kagarko', NOW(), NOW()),
(19, 'Kajuru', NOW(), NOW()),
(19, 'Kaura', NOW(), NOW()),
(19, 'Kauru', NOW(), NOW()),
(19, 'Kubau', NOW(), NOW()),
(19, 'Kudan', NOW(), NOW()),
(19, 'Lere', NOW(), NOW()),
(19, 'Makarfi', NOW(), NOW()),
(19, 'Sabon Gari', NOW(), NOW()),
(19, 'Sanga', NOW(), NOW()),
(19, 'Soba', NOW(), NOW()),
(19, 'Zangon Kataf', NOW(), NOW()),
(19, 'Zaria', NOW(), NOW());

-- OYO STATE LGAs (state_id = 31)
INSERT INTO lgas (state_id, name, created_at, updated_at) VALUES
(31, 'Afijio', NOW(), NOW()),
(31, 'Akinyele', NOW(), NOW()),
(31, 'Atiba', NOW(), NOW()),
(31, 'Atisbo', NOW(), NOW()),
(31, 'Egbeda', NOW(), NOW()),
(31, 'Ibadan North', NOW(), NOW()),
(31, 'Ibadan North-East', NOW(), NOW()),
(31, 'Ibadan North-West', NOW(), NOW()),
(31, 'Ibadan South-East', NOW(), NOW()),
(31, 'Ibadan South-West', NOW(), NOW()),
(31, 'Ibarapa Central', NOW(), NOW()),
(31, 'Ibarapa East', NOW(), NOW()),
(31, 'Ibarapa North', NOW(), NOW()),
(31, 'Ido', NOW(), NOW()),
(31, 'Irepo', NOW(), NOW()),
(31, 'Iseyin', NOW(), NOW()),
(31, 'Itesiwaju', NOW(), NOW()),
(31, 'Iwajowa', NOW(), NOW()),
(31, 'Kajola', NOW(), NOW()),
(31, 'Lagelu', NOW(), NOW()),
(31, 'Ogbomosho North', NOW(), NOW()),
(31, 'Ogbomosho South', NOW(), NOW()),
(31, 'Ogo Oluwa', NOW(), NOW()),
(31, 'Olorunsogo', NOW(), NOW()),
(31, 'Oluyole', NOW(), NOW()),
(31, 'Ona Ara', NOW(), NOW()),
(31, 'Orelope', NOW(), NOW()),
(31, 'Ori Ire', NOW(), NOW()),
(31, 'Oyo East', NOW(), NOW()),
(31, 'Oyo West', NOW(), NOW()),
(31, 'Saki East', NOW(), NOW()),
(31, 'Saki West', NOW(), NOW()),
(31, 'Surulere', NOW(), NOW());

-- RIVERS STATE LGAs (state_id = 33)
INSERT INTO lgas (state_id, name, created_at, updated_at) VALUES
(33, 'Abua/Odual', NOW(), NOW()),
(33, 'Ahoada East', NOW(), NOW()),
(33, 'Ahoada West', NOW(), NOW()),
(33, 'Akuku-Toru', NOW(), NOW()),
(33, 'Andoni', NOW(), NOW()),
(33, 'Asari-Toru', NOW(), NOW()),
(33, 'Bonny', NOW(), NOW()),
(33, 'Degema', NOW(), NOW()),
(33, 'Eleme', NOW(), NOW()),
(33, 'Emuoha', NOW(), NOW()),
(33, 'Etche', NOW(), NOW()),
(33, 'Gokana', NOW(), NOW()),
(33, 'Ikwerre', NOW(), NOW()),
(33, 'Khana', NOW(), NOW()),
(33, 'Obio/Akpor', NOW(), NOW()),
(33, 'Ogba/Egbema/Ndoni', NOW(), NOW()),
(33, 'Ogu/Bolo', NOW(), NOW()),
(33, 'Okrika', NOW(), NOW()),
(33, 'Omuma', NOW(), NOW()),
(33, 'Opobo/Nkoro', NOW(), NOW()),
(33, 'Oyigbo', NOW(), NOW()),
(33, 'Port Harcourt', NOW(), NOW()),
(33, 'Tai', NOW(), NOW());

-- FCT ABUJA Area Councils (state_id = 15)
INSERT INTO lgas (state_id, name, created_at, updated_at) VALUES
(15, 'Abaji', NOW(), NOW()),
(15, 'Abuja Municipal', NOW(), NOW()),
(15, 'Bwari', NOW(), NOW()),
(15, 'Gwagwalada', NOW(), NOW()),
(15, 'Kuje', NOW(), NOW()),
(15, 'Kwali', NOW(), NOW());

-- =====================================================
-- INSERT LIBERIAN DISTRICTS (LGAs equivalent)
-- =====================================================

-- MONTSERRADO COUNTY DISTRICTS (state_id = 48)
INSERT INTO lgas (state_id, name, created_at, updated_at) VALUES
(48, 'Greater Monrovia', NOW(), NOW()),
(48, 'Careysburg', NOW(), NOW()),
(48, 'Commonwealth', NOW(), NOW()),
(48, 'Todee', NOW(), NOW()),
(48, 'St. Paul River', NOW(), NOW());

-- NIMBA COUNTY DISTRICTS (state_id = 49)
INSERT INTO lgas (state_id, name, created_at, updated_at) VALUES
(49, 'Sanniquellie-Mahn', NOW(), NOW()),
(49, 'Tappita', NOW(), NOW()),
(49, 'Yarwin', NOW(), NOW()),
(49, 'Doe', NOW(), NOW()),
(49, 'Gbehlay-Geh', NOW(), NOW()),
(49, 'Saclepea-Mahn', NOW(), NOW());

-- BONG COUNTY DISTRICTS (state_id = 39)
INSERT INTO lgas (state_id, name, created_at, updated_at) VALUES
(39, 'Gbarnga', NOW(), NOW()),
(39, 'Fuamah', NOW(), NOW()),
(39, 'Jorquelleh', NOW(), NOW()),
(39, 'Kokoyah', NOW(), NOW()),
(39, 'Panta-Kpa', NOW(), NOW()),
(39, 'Salala', NOW(), NOW()),
(39, 'Sanoyea', NOW(), NOW()),
(39, 'Suakoko', NOW(), NOW()),
(39, 'Tukpahblee', NOW(), NOW()),
(39, 'Yeallequelleh', NOW(), NOW());

-- LOFA COUNTY DISTRICTS (state_id = 45)
INSERT INTO lgas (state_id, name, created_at, updated_at) VALUES
(45, 'Voinjama', NOW(), NOW()),
(45, 'Kolahun', NOW(), NOW()),
(45, 'Foya', NOW(), NOW()),
(45, 'Salayea', NOW(), NOW()),
(45, 'Vahun', NOW(), NOW()),
(45, 'Zorzor', NOW(), NOW());

-- GRAND BASSA COUNTY DISTRICTS (state_id = 41)
INSERT INTO lgas (state_id, name, created_at, updated_at) VALUES
(41, 'Buchanan', NOW(), NOW()),
(41, 'Compound #3', NOW(), NOW()),
(41, 'District #1', NOW(), NOW()),
(41, 'District #2', NOW(), NOW()),
(41, 'District #3', NOW(), NOW()),
(41, 'District #4', NOW(), NOW()),
(41, 'Owensgrove', NOW(), NOW()),
(41, 'St. John River', NOW(), NOW());