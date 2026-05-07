# QSG License System - API Documentation

## Overview
This API allows Android mod menu apps to verify license keys and authenticate users.

**Base URL:** `https://hclou.com`

---

## Authentication Endpoint

### POST `/connect`

Verify a license key and authenticate the device.

#### Request Headers
```
Content-Type: application/x-www-form-urlencoded
```

#### Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `game` | string | Yes | Package ID of the game (e.g., `com.tencent.ig` for PUBG Mobile) |
| `user_key` | string | Yes | License key provided by user |
| `serial` | string | Yes | Unique device identifier (UUID generated from Android ID + Device Model + Brand) |

#### Example Request
```
POST https://hclou.com/connect
Content-Type: application/x-www-form-urlencoded

game=com.tencent.ig&user_key=ABC123XYZ456&serial=550e8400-e29b-41d4-a716-446655440000
```

#### Success Response

**Status:** `200 OK`

```json
{
  "status": true,
  "data": {
    "modname": "NOCASHRANDI",
    "mod_status": "on",
    "credit": "NOCASHRANDI",
    "token": "a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6",
    "device": 1,
    "EXP": "2026-05-10 15:30:00",
    "rng": 1746582960
  }
}
```

**Response Fields:**
- `status` (boolean): Authentication result
- `data.modname` (string): Mod menu name to display
- `data.mod_status` (string): Mod status ("on" or "off")
- `data.credit` (string): Credit text to display
- `data.token` (string): MD5 hash for verification
- `data.device` (integer): Maximum devices allowed
- `data.EXP` (datetime): License expiration date
- `data.rng` (integer): Unix timestamp for token validation

#### Error Responses

**Invalid Key:**
```json
{
  "status": false,
  "reason": "USER OR GAME NOT REGISTERED"
}
```

**Expired Key:**
```json
{
  "status": false,
  "reason": "EXPIRED KEY"
}
```

**Blocked User:**
```json
{
  "status": false,
  "reason": "USER BLOCKED"
}
```

**Max Devices Reached:**
```json
{
  "status": false,
  "reason": "MAX DEVICE REACHED"
}
```

**Invalid Parameters:**
```json
{
  "status": false,
  "reason": "INVALID PARAMETER"
}
```

---

## Token Verification

To verify the token is authentic:

1. Concatenate: `game + "-" + user_key + "-" + serial + "-" + staticWords`
2. Calculate MD5 hash of the concatenated string
3. Compare with the `token` from response
4. Verify `rng + 30 > current_time` (token valid for 30 seconds)

**Static Words:** `Vm8Lk7Uj2JmsjCPVPVjrLa7zgfx3uz9E`

### Example Verification (C++)
```cpp
std::string auth = game;           // "com.tencent.ig"
auth += "-";
auth += user_key;                  // "ABC123XYZ456"
auth += "-";
auth += serial;                    // "550e8400-e29b-41d4-a716-446655440000"
auth += "-";
auth += "Vm8Lk7Uj2JmsjCPVPVjrLa7zgfx3uz9E";

std::string calculatedToken = CalcMD5(auth);
bool isValid = (calculatedToken == responseToken) && (rng + 30 > time(0));
```

---

## Device ID Generation

Generate a unique device identifier using Android APIs:

```cpp
// Get Android ID
jclass contextClass = env->FindClass("android/content/Context");
jmethodID getContentResolverMethod = env->GetMethodID(contextClass, "getContentResolver", "()Landroid/content/ContentResolver;");
jclass settingSecureClass = env->FindClass("android/provider/Settings$Secure");
jmethodID getStringMethod = env->GetStaticMethodID(settingSecureClass, "getString", "(Landroid/content/ContentResolver;Ljava/lang/String;)Ljava/lang/String;");
auto obj = env->CallObjectMethod(context, getContentResolverMethod);
auto str = (jstring) env->CallStaticObjectMethod(settingSecureClass, getStringMethod, obj, env->NewStringUTF("android_id"));
const char* androidId = env->GetStringUTFChars(str, 0);

// Get Device Model
jclass buildClass = env->FindClass("android/os/Build");
jfieldID modelId = env->GetStaticFieldID(buildClass, "MODEL", "Ljava/lang/String;");
auto modelStr = (jstring) env->GetStaticObjectField(buildClass, modelId);
const char* model = env->GetStringUTFChars(modelStr, 0);

// Get Device Brand
jfieldID brandId = env->GetStaticFieldID(buildClass, "BRAND", "Ljava/lang/String;");
auto brandStr = (jstring) env->GetStaticObjectField(buildClass, brandId);
const char* brand = env->GetStringUTFChars(brandStr, 0);

// Concatenate and generate UUID
std::string hwid = std::string(androidId) + model + brand;
std::string uuid = GenerateUUIDFromBytes(hwid);
```

---

## Package IDs

Common game package IDs:

| Game | Package ID |
|------|------------|
| PUBG Mobile Global | `com.tencent.ig` |
| PUBG Mobile KR | `com.pubg.krmobile` |
| PUBG Mobile VN | `com.vng.pubgmobile` |
| Free Fire | `com.dts.freefireth` |
| Mobile Legends | `com.mobile.legends` |
| Call of Duty Mobile | `com.activision.callofduty.shooter` |

**Note:** Use the actual package ID from `context.getPackageName()` for automatic detection.

---

## Complete Integration Example (C++)

```cpp
#include <curl/curl.h>
#include <json.hpp>

using json = nlohmann::json;

struct MemoryStruct {
    char *memory;
    size_t size;
};

static size_t WriteMemoryCallback(void *contents, size_t size, size_t nmemb, void *userp) {
    size_t realsize = size * nmemb;
    struct MemoryStruct *mem = (struct MemoryStruct *) userp;
    mem->memory = (char *) realloc(mem->memory, mem->size + realsize + 1);
    if (mem->memory == NULL) return 0;
    memcpy(&(mem->memory[mem->size]), contents, realsize);
    mem->size += realsize;
    mem->memory[mem->size] = 0;
    return realsize;
}

bool VerifyLicense(JNIEnv *env, jobject context, const char* userKey) {
    // Get package name (game ID)
    const char* packageName = GetPackageName(env, context);
    
    // Generate device UUID
    std::string hwid = GetAndroidID(env, context);
    hwid += GetDeviceModel(env);
    hwid += GetDeviceBrand(env);
    std::string UUID = GetDeviceUniqueIdentifier(env, hwid.c_str());
    
    // Prepare POST data
    char data[4096];
    sprintf(data, "game=%s&user_key=%s&serial=%s", packageName, userKey, UUID.c_str());
    
    // Initialize CURL
    struct MemoryStruct chunk{};
    chunk.memory = (char *) malloc(1);
    chunk.size = 0;
    
    CURL *curl = curl_easy_init();
    if (!curl) return false;
    
    curl_easy_setopt(curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_easy_setopt(curl, CURLOPT_URL, "https://hclou.com/connect");
    curl_easy_setopt(curl, CURLOPT_FOLLOWLOCATION, 1L);
    
    struct curl_slist *headers = NULL;
    headers = curl_slist_append(headers, "Content-Type: application/x-www-form-urlencoded");
    curl_easy_setopt(curl, CURLOPT_HTTPHEADER, headers);
    curl_easy_setopt(curl, CURLOPT_POSTFIELDS, data);
    curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, WriteMemoryCallback);
    curl_easy_setopt(curl, CURLOPT_WRITEDATA, (void *) &chunk);
    curl_easy_setopt(curl, CURLOPT_SSL_VERIFYPEER, 0L);
    curl_easy_setopt(curl, CURLOPT_SSL_VERIFYHOST, 0L);
    
    CURLcode res = curl_easy_perform(curl);
    bool bValid = false;
    
    if (res == CURLE_OK) {
        try {
            json result = json::parse(chunk.memory);
            if (result["status"] == true) {
                std::string token = result["data"]["token"].get<std::string>();
                time_t rng = result["data"]["rng"].get<time_t>();
                
                // Verify token within 30 seconds
                if (rng + 30 > time(0)) {
                    std::string auth = packageName;
                    auth += "-";
                    auth += userKey;
                    auth += "-";
                    auth += UUID;
                    auth += "-";
                    auth += "Vm8Lk7Uj2JmsjCPVPVjrLa7zgfx3uz9E";
                    
                    std::string calculatedToken = CalcMD5(auth);
                    bValid = (token == calculatedToken);
                }
            }
        } catch (json::exception &e) {
            // Handle JSON parse error
        }
    }
    
    curl_easy_cleanup(curl);
    free(chunk.memory);
    
    return bValid;
}
```

---

## Rate Limiting

- No rate limit currently enforced
- Recommended: Cache validation result for 5-10 minutes
- Re-validate on app restart or after expiration

---

## Support

For issues or questions:
- Telegram: [@QMODZzZ](https://t.me/QMODZzZ)
- Website: [hclou.com](https://hclou.com)

---

## Changelog

### v1.0 (2026-05-07)
- Initial API release
- Package-based authentication system
- Multi-device support
- Token verification with MD5

---

**Last Updated:** May 7, 2026
