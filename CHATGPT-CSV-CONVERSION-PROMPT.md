# 🤖 ChatGPT Prompt: Convert Travel Packages to CSV with HTML Formatting

---

## 📋 COPY THIS ENTIRE PROMPT TO CHATGPT

```
I need you to convert travel package data from Word/text format into a CSV file format for WordPress import. You are an expert data conversion specialist with deep knowledge of HTML formatting and CSV structure.

## YOUR ROLE & EXPERTISE:
- Expert in travel package data structuring
- Proficient in HTML formatting for web content
- Skilled in CSV file creation with proper escaping
- Meticulous attention to detail for error-free output

## CSV FILE STRUCTURE YOU MUST FOLLOW:

### REQUIRED COLUMNS (in exact order):
1. title
2. subtitle
3. description
4. days
5. nights
6. price
7. featured_image_url
8. categories
9. package_types
10. activity_types
11. amenities
12. difficulty_level
13. season
14. duration
15. reason
16. day_1_title
17. day_1_activities
18. day_2_title
19. day_2_activities
... continue up to day_15_title and day_15_activities

## DETAILED FIELD SPECIFICATIONS:

### 1. BASIC PACKAGE INFORMATION

**title** (Required)
- Clean package title
- Example: "Manali Adventure Tour 5D/4N"
- No HTML, plain text only

**subtitle** (Optional)
- Brief tagline or subtitle
- Example: "Explore the Himalayan Paradise"
- No HTML, plain text only

**description** (Required - HTML Formatted)
- MUST include HTML tags
- Use <p> for paragraphs
- Use <strong> or <b> for bold text
- Use <em> or <i> for italic text
- Use <ul><li> for bullet lists
- Use <ol><li> for numbered lists
- Wrap entire content in quotes
- Example: "<p>Experience the <strong>breathtaking beauty</strong> of Manali.</p><ul><li>Mountain trekking</li><li>River rafting</li><li>Cultural experiences</li></ul>"

**days** (Required)
- Number only
- Example: 5

**nights** (Required)
- Number only
- Example: 4

**price** (Optional)
- Number or text
- Example: "25000" or "₹25,000" or "Contact for pricing"

**featured_image_url** (Optional)
- Direct image URL only
- Leave empty if not available
- Example: "https://example.com/images/manali.jpg"

### 2. TAXONOMY FIELDS (Comma-separated values)

**categories** (Recommended Taxonomy Terms)
Select appropriate terms from:
- Mountain Tours
- Beach Tours
- Adventure Tours
- Honeymoon Packages
- Family Tours
- Group Tours
- Luxury Tours
- Budget Tours
- Pilgrimage Tours
- Wildlife Tours
- Heritage Tours
- Cultural Tours
- Trekking Tours
- Weekend Getaways
- International Tours
- Domestic Tours

Format: "Mountain Tours, Adventure Tours, Trekking Tours"

**package_types**
Select from:
- Adventure Package
- Leisure Package
- Romantic Package
- Honeymoon Package
- Family Package
- Group Package
- Solo Travel Package
- Corporate Package
- Customized Package
- Fixed Departure
- Couple Package
- Student Package
- Senior Citizen Package

Format: "Adventure Package, Couple Package"

**activity_types**
Select from:
- Trekking
- Hiking
- Camping
- River Rafting
- Paragliding
- Skiing
- Snowboarding
- Rock Climbing
- Zip Lining
- Mountain Biking
- Wildlife Safari
- Bird Watching
- Photography
- Sightseeing
- Temple Visits
- Beach Activities
- Water Sports
- Scuba Diving
- Snorkeling
- Kayaking
- Boating
- Cultural Tours
- Village Walks
- Food Tours
- Shopping
- Spa & Wellness
- Yoga & Meditation

Format: "Trekking, Camping, Paragliding"

**amenities**
Select from:
- Breakfast Included
- Lunch Included
- Dinner Included
- All Meals Included
- AC Accommodation
- Non-AC Accommodation
- 3-Star Hotel
- 4-Star Hotel
- 5-Star Hotel
- Resort Stay
- Camp Stay
- Homestay
- WiFi Available
- Transport Included
- Airport Pickup
- Airport Drop
- Sightseeing Vehicle
- Driver Allowance
- Fuel Charges Included
- Parking Charges
- Tour Guide
- English Speaking Guide
- Travel Insurance
- First Aid Kit
- Adventure Equipment
- Professional Photographer
- Bonfire
- Music System
- Welcome Drink
- Complimentary Gift

Format: "Breakfast Included, WiFi Available, Tour Guide"

**difficulty_level** (Single value only)
Choose ONE from:
- Easy
- Beginner
- Moderate
- Intermediate
- Challenging
- Hard
- Difficult
- Expert
- Advanced

Format: "Moderate"

**season** (Comma-separated)
Select from:
- All Year Round
- Summer
- Winter
- Monsoon
- Spring
- Autumn
- Post-Monsoon
- Peak Season
- Off Season

Format: "Summer, Spring"

**duration** (Single value)
Select from:
- 1-2 Days
- 3-4 Days
- 5-7 Days
- 8-10 Days
- 11-15 Days
- 15+ Days
- Weekend
- Week Long
- Fortnight
- Month Long

Format: "5-7 Days"

**reason** (Comma-separated)
Select from:
- Adventure
- Nature
- Spirituality
- Culture
- History
- Photography
- Honeymoon
- Family Bonding
- Relaxation
- Thrill
- Learning
- Exploration
- Romance
- Celebration
- Retreat
- Detox
- Fitness
- Wellness
- Entertainment
- Shopping

Format: "Adventure, Nature, Photography"

### 3. DAY-BY-DAY ITINERARY (CRITICAL SECTION)

For EACH day of the itinerary:

**day_X_title** (Plain text)
- Brief day title
- NO HTML
- Example: "Arrival in Manali & Local Sightseeing"

**day_X_activities** (HTML Formatted - MOST IMPORTANT)
- MUST use proper HTML structure
- REQUIRED format for maximum compatibility:

```html
<p><strong>Morning:</strong> Activity description here</p>
<p><strong>Afternoon:</strong> Activity description here</p>
<p><strong>Evening:</strong> Activity description here</p>
<ul>
<li>Sub-activity or highlight 1</li>
<li>Sub-activity or highlight 2</li>
<li>Sub-activity or highlight 3</li>
</ul>
```

**IMPORTANT HTML RULES:**
1. Always wrap in double quotes: "..."
2. Use <p> tags for each time block or paragraph
3. Use <strong> for time labels (Morning, Afternoon, Evening)
4. Use <ul><li> for activity lists
5. Use <ol><li> for numbered steps if needed
6. NO line breaks outside HTML tags
7. NO special characters without encoding
8. Keep HTML on single line per cell

**EXAMPLE - CORRECT FORMAT:**
```csv
day_1_title,day_1_activities
"Arrival in Manali","<p><strong>Morning:</strong> Arrive at Manali bus stand/airport. Transfer to hotel and check-in.</p><p><strong>Afternoon:</strong> Rest and acclimatization. Enjoy welcome drink and hotel briefing.</p><p><strong>Evening:</strong> Visit Mall Road for shopping and local food tasting.</p><ul><li>Visit Hadimba Temple</li><li>Explore Old Manali</li><li>Dinner at hotel</li></ul><p>Overnight stay at hotel.</p>"
```

**EXAMPLE - WRONG FORMAT (DO NOT USE):**
```
day_1_activities: Morning: Arrive at hotel
Afternoon: Rest
(This is wrong - no HTML tags!)
```

### 4. CSV FORMATTING RULES (CRITICAL):

1. **Header Row First:**
   - First line must be: title,subtitle,description,days,nights,price,featured_image_url,categories,package_types,activity_types,amenities,difficulty_level,season,duration,reason,day_1_title,day_1_activities,day_2_title,day_2_activities,...

2. **Quote All Text Fields:**
   - Use double quotes: "text content here"
   - If text contains quotes, escape them: ""quoted text""

3. **HTML Content:**
   - MUST be wrapped in quotes
   - NO line breaks inside HTML
   - Example: "<p>Content</p><ul><li>Item</li></ul>"

4. **Empty Fields:**
   - Leave completely empty (no quotes)
   - Example: title,subtitle,description,,,price,

5. **Commas in Content:**
   - Always wrap field in quotes if it contains commas
   - Example: "Mountain Tours, Adventure Tours, Trekking"

6. **Number of Days:**
   - If package has 5 days, provide day_1 through day_5
   - Leave remaining day columns empty
   - Do NOT include day_6 to day_15 if package is only 5 days

### 5. QUALITY CHECKS YOU MUST PERFORM:

Before providing the CSV, verify:
- ✅ All required fields filled (title, description, days, nights)
- ✅ All HTML is properly formatted with tags
- ✅ All quotes are properly escaped
- ✅ Taxonomy terms match provided lists
- ✅ No unescaped line breaks in cells
- ✅ Day numbers are sequential (day_1, day_2, day_3...)
- ✅ Each day has both title AND activities
- ✅ Activities use proper HTML structure
- ✅ No orphaned HTML tags (all tags closed)
- ✅ CSV header matches exact order specified

### 6. OUTPUT FORMAT:

Provide output in this structure:

```
PACKAGE NAME: [Package Title]
---

CSV DATA:
```csv
[Paste complete CSV here with header and data row]
```

SUMMARY:
- Total Days: X
- Categories: [list]
- Package Type: [list]
- Activities: [list]
```

### 7. EXAMPLE COMPLETE CSV ROW:

```csv
title,subtitle,description,days,nights,price,featured_image_url,categories,package_types,activity_types,amenities,difficulty_level,season,duration,reason,day_1_title,day_1_activities,day_2_title,day_2_activities,day_3_title,day_3_activities
"Manali Adventure Tour 5D/4N","Experience the Himalayan Beauty","<p>Embark on an unforgettable journey to <strong>Manali</strong>, the valley of gods. Experience thrilling adventures, serene landscapes, and rich cultural heritage.</p><ul><li>Professional guide throughout the tour</li><li>Comfortable accommodation</li><li>All adventure equipment provided</li></ul><p>This package is perfect for <em>adventure enthusiasts</em> and nature lovers.</p>",5,4,"25000","","Mountain Tours, Adventure Tours, Honeymoon Packages","Adventure Package, Couple Package","Trekking, Paragliding, River Rafting, Sightseeing","Breakfast Included, WiFi Available, Tour Guide, Transport Included, Adventure Equipment","Moderate","Summer, Spring","5-7 Days","Adventure, Nature, Romance","Arrival in Manali & Local Sightseeing","<p><strong>Morning:</strong> Arrive at Manali bus stand. Our representative will greet you and transfer you to the hotel.</p><p><strong>Afternoon:</strong> Check-in to hotel and freshen up. Enjoy welcome drink and orientation briefing about the tour.</p><p><strong>Evening:</strong> Visit Mall Road for shopping and local cuisine tasting. Explore the bustling markets.</p><ul><li>Visit Hadimba Devi Temple</li><li>Explore Old Manali cafes</li><li>Riverside walk</li><li>Dinner at hotel</li></ul><p>Overnight stay at hotel in Manali.</p>","Solang Valley Full Day Adventure","<p><strong>Early Morning:</strong> After breakfast, depart for Solang Valley, known as the adventure capital of Himachal.</p><p><strong>Morning to Afternoon:</strong> Enjoy thrilling adventure activities at Solang Valley.</p><ul><li>Paragliding with certified instructors</li><li>Zorbing (weather permitting)</li><li>Cable car ride to snow point</li><li>Rope way adventure</li></ul><p><strong>Afternoon:</strong> Lunch at local restaurant (own expense).</p><p><strong>Evening:</strong> Return to hotel. Free time for leisure.</p><p>Overnight stay at hotel.</p>","Rohtang Pass Excursion","<p><strong>Early Morning:</strong> Start early for Rohtang Pass (subject to permit availability and weather conditions).</p><p><strong>Morning:</strong> Experience snow activities at Rohtang Pass.</p><ul><li>Snow scooter rides</li><li>Sledding on snow</li><li>Photography at scenic viewpoints</li><li>Visit glaciers</li></ul><p><strong>Afternoon:</strong> Drive back to Manali via Rahala Falls.</p><p><strong>Evening:</strong> Visit Vashisht hot water springs and temple.</p><p>Overnight stay at hotel.</p>"
```

### 8. SPECIAL INSTRUCTIONS:

**When converting Word document:**
1. Extract package title as is
2. Convert main description to HTML with proper tags
3. Count total days in itinerary
4. For each day:
   - Extract day heading as day_X_title
   - Convert all activities to HTML format
   - Use time-based structure (Morning/Afternoon/Evening)
   - Convert bullet points to <ul><li> lists
5. Identify and map to taxonomy terms
6. Keep formatting consistent across all packages

**Common Word to HTML conversions:**
- Bold text → <strong>text</strong>
- Italic text → <em>text</em>
- Bullet point → <li>text</li> (wrap in <ul>)
- Numbered list → <li>text</li> (wrap in <ol>)
- New paragraph → <p>text</p>
- Heading → <h3>text</h3> or keep as <strong>

---

## YOUR TASK:
Convert the travel package data I provide into CSV format following ALL rules above. Ensure:
1. Proper HTML formatting for descriptions and itinerary
2. Correct taxonomy term mapping
3. No formatting errors
4. Complete CSV structure
5. Quality checks performed

Are you ready? I'll provide the Word document data now.
```

---

## 📖 HOW TO USE THIS PROMPT:

### Step 1: Copy Entire Prompt
- Copy everything between the ``` markers above
- Paste into ChatGPT

### Step 2: Provide Your Word Data
After ChatGPT confirms it's ready, paste your Word document content like:

```
PACKAGE: Manali Adventure Tour

Duration: 5 Days / 4 Nights
Price: ₹25,000

Description:
Experience the beauty of Manali with mountain trekking, river rafting, and more.

Day 1: Arrival
Morning: Reach Manali
Afternoon: Hotel check-in
Evening: Mall Road visit

Day 2: Solang Valley
Full day at Solang Valley
- Paragliding
- Zorbing
- Cable car

[etc...]
```

### Step 3: Get CSV Output
ChatGPT will provide:
- ✅ Properly formatted CSV
- ✅ HTML-formatted itinerary
- ✅ Correct taxonomy mapping
- ✅ Ready to import!

---

## 🎯 RESULT:
You'll get a perfect CSV file that:
- ✅ Imports without errors
- ✅ Preserves all formatting
- ✅ Correctly categorized
- ✅ Professional HTML structure
- ✅ Ready for WordPress import!

---

## 💡 PRO TIPS:

1. **For Multiple Packages:**
   - Process one at a time for accuracy
   - Then combine all rows into single CSV

2. **For Complex Formatting:**
   - Mention special requirements upfront
   - ChatGPT will handle them properly

3. **For Custom Taxonomies:**
   - Add your custom terms to the taxonomy lists in prompt
   - ChatGPT will use them

---

**Save this prompt and reuse for all your package conversions!** 🚀
