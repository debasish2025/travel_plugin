# 📥 CSV Import Guide for Travel Packages

## 🎯 Overview

Import unlimited travel packages with **full HTML formatting support** directly from Word documents!

---

## 🚀 Quick Start

1. **Go to:** WordPress Admin → Travel Packages → 📥 Import CSV
2. **Download Template:** Click "Download CSV Template" button
3. **Fill in Your Data:** Copy from Word, paste into Excel/Google Sheets
4. **Upload:** Upload the CSV and click "Start Import"

---

## 📋 CSV Format

### Required Columns

| Column Name | Description | Example |
|------------|-------------|---------|
| `title` | Package title | `Manali Adventure Tour` |
| `description` | Full description with HTML | `<p>Amazing tour to <strong>Manali</strong></p>` |
| `days` | Number of days | `5` |
| `nights` | Number of nights | `4` |

### Optional Columns

| Column Name | Description | Example |
|------------|-------------|---------|
| `subtitle` | Package subtitle | `Explore the Mountains` |
| `price` | Package price | `25000` |
| `featured_image_url` | Image URL | `https://example.com/image.jpg` |
| `categories` | Comma-separated | `Mountain Tours, Adventure` |
| `package_types` | Comma-separated | `Adventure Package, Couple Package` |
| `activity_types` | Comma-separated | `Trekking, Rafting` |
| `amenities` | Comma-separated | `WiFi, Meals Included` |
| `difficulty_level` | Single value | `Moderate` |
| `season` | Comma-separated | `Summer, Spring` |
| `duration` | Single value | `5-7 Days` |
| `reason` | Comma-separated | `Adventure, Nature` |

### Itinerary Columns (Day by Day)

**Supports up to 15 days!**

| Column Name | Description | Example |
|------------|-------------|---------|
| `day_1_title` | Day 1 title | `Arrival in Manali` |
| `day_1_activities` | Day 1 activities with HTML | `<p><strong>Morning:</strong> Arrive</p><ul><li>Check-in</li></ul>` |
| `day_2_title` | Day 2 title | `Solang Valley` |
| `day_2_activities` | Day 2 activities with HTML | `<p>Full day at valley</p>` |
| ... | ... | ... |
| `day_15_title` | Day 15 title | `Departure` |
| `day_15_activities` | Day 15 activities | `<p>Check-out</p>` |

---

## ✨ HTML Formatting Support

### Copy Directly from Word!

The importer supports **full HTML formatting** - just copy from Word and paste into Excel/Sheets!

### Supported HTML Tags

#### Text Formatting
- `<strong>` or `<b>` → **Bold text**
- `<em>` or `<i>` → *Italic text*
- `<u>` → Underlined text

#### Lists
```html
<ul>
  <li>Bullet point 1</li>
  <li>Bullet point 2</li>
</ul>

<ol>
  <li>Numbered item 1</li>
  <li>Numbered item 2</li>
</ol>
```

#### Paragraphs
```html
<p>Paragraph text here</p>
```

#### Headings
```html
<h3>Sub-heading</h3>
<h4>Smaller heading</h4>
```

#### Links
```html
<a href="https://example.com">Link text</a>
```

---

## 📝 Example CSV Row

```csv
title,description,days,nights,day_1_title,day_1_activities,day_2_title,day_2_activities
"Manali Tour","<p>Explore <strong>Manali</strong> mountains</p><ul><li>Trekking</li><li>Rafting</li></ul>",5,4,"Arrival","<p><strong>Morning:</strong> Arrive at hotel</p><ul><li>Welcome drink</li><li>Briefing</li></ul>","Solang Valley","<p>Full day at <strong>Solang Valley</strong></p><ul><li>Paragliding</li><li>Zorbing</li></ul>"
```

---

## 💡 Pro Tips

### 1. Copying from Word to Excel

**Step-by-step:**
1. Select text in Word (with formatting)
2. Copy (Ctrl+C)
3. Paste into Excel cell
4. Excel preserves the HTML automatically!

### 2. Multiple Lines in One Cell

In Excel/Google Sheets:
- Press `Alt+Enter` (Windows) or `Option+Enter` (Mac) for line breaks
- Or just paste HTML directly

### 3. Categories & Taxonomies

**Format:** Comma-separated values
```
Mountain Tours, Adventure, Summer Packages
```

**Auto-create:** Enable "Auto-create missing categories" option during import

### 4. Featured Images

**Options:**
- Provide direct image URL: `https://yoursite.com/images/package.jpg`
- Leave empty to add images later
- Images are automatically downloaded and attached

---

## ⚙️ Import Options

### Skip Duplicates
✅ **Recommended: ON**
- Checks package title
- Skips if already exists
- Prevents duplicate content

### Auto-create Categories
✅ **Recommended: ON**
- Creates missing taxonomy terms
- No manual setup needed
- All categories auto-generated

### Publish Immediately
⚠️ **Default: OFF**
- ON: Packages go live immediately
- OFF: Saves as draft for review
- Recommended to review first

---

## 📊 Import Results

After import, you'll see:
- ✅ **Imported:** Successfully created packages
- ⏭️ **Skipped:** Duplicates or empty rows
- ❌ **Errors:** Failed imports with details
- 📈 **Total:** All processed rows

---

## 🔥 Advanced Tips

### 1. Bulk Import from Word

**Workflow:**
1. Have all packages in Word document
2. Copy each package content
3. Paste into corresponding Excel columns
4. All formatting preserved!

### 2. Itinerary Best Practices

**Structure each day like this:**
```html
<p><strong>Morning:</strong> Activity description</p>
<p><strong>Afternoon:</strong> Activity description</p>
<ul>
  <li>Sub-activity 1</li>
  <li>Sub-activity 2</li>
</ul>
```

### 3. SEO-Friendly Descriptions

Include in description:
- **Keywords** in `<strong>` tags
- **Location names** in headings
- **Bullet points** for features
- **Call-to-action** at end

---

## 🐛 Troubleshooting

### Issue: HTML not rendering

**Solution:** Make sure HTML is properly enclosed:
```csv
"<p>Text with <strong>bold</strong></p>"
```
Note the quotes around the cell!

### Issue: Categories not created

**Solution:** Enable "Auto-create missing categories" option

### Issue: Image not importing

**Solution:**
- Check URL is accessible
- Use direct image link (not Google Drive/Dropbox preview links)
- Ensure proper permissions

### Issue: Itinerary not showing

**Solution:**
- Check day titles are not empty
- Verify column names: `day_1_title`, `day_1_activities`
- Make sure activities have content

---

## 📞 Support

Need help? Check:
1. Sample template (included in download)
2. This guide
3. WordPress admin import page for real-time help

---

## 🎉 Success!

You can now import **hundreds of packages** in minutes with **full formatting** from your Word documents!

**Happy Importing! 🚀**
