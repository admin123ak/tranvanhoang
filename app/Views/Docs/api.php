<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>API Documentation</h3>
                <p class="text-subtitle text-muted">Complete Integration Guide for Developers</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">API Docs</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">📋 Overview</h4>
                    </div>
                    <div class="card-body">
                        <p>This API allows Android mod menu applications to verify license keys and authenticate user devices. The system supports multi-game packages, device tracking, and expiration management.</p>
                        <div class="alert alert-light-primary">
                            <strong>Base URL:</strong> <code>https://hclou.com</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">🔌 Authentication Endpoint</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-light-success">
                            <span class="badge bg-success">POST</span> <code>/connect</code>
                        </div>
                        <p>Verify a license key and authenticate the device.</p>

                        <h5 class="mt-4">Request Parameters</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Type</th>
                                        <th>Required</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>game</code></td>
                                        <td>string</td>
                                        <td><span class="badge bg-danger">Required</span></td>
                                        <td>Package ID of the game (e.g., <code>com.tencent.ig</code>)</td>
                                    </tr>
                                    <tr>
                                        <td><code>user_key</code></td>
                                        <td>string</td>
                                        <td><span class="badge bg-danger">Required</span></td>
                                        <td>License key provided by user</td>
                                    </tr>
                                    <tr>
                                        <td><code>serial</code></td>
                                        <td>string</td>
                                        <td><span class="badge bg-danger">Required</span></td>
                                        <td>Unique device identifier (UUID)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h5 class="mt-4">Example Request</h5>
                        <pre class="language-bash"><code>POST https://hclou.com/connect
Content-Type: application/x-www-form-urlencoded

game=com.tencent.ig&user_key=ABC123XYZ456&serial=550e8400-e29b-41d4-a716-446655440000</code></pre>

                        <h5 class="mt-4">Success Response</h5>
                        <pre class="language-json"><code>{
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
}</code></pre>

                        <h5 class="mt-4">Response Fields</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>status</code></td>
                                        <td>boolean</td>
                                        <td>Authentication result (true/false)</td>
                                    </tr>
                                    <tr>
                                        <td><code>data.modname</code></td>
                                        <td>string</td>
                                        <td>Mod menu name to display</td>
                                    </tr>
                                    <tr>
                                        <td><code>data.mod_status</code></td>
                                        <td>string</td>
                                        <td>Mod status ("on" or "off")</td>
                                    </tr>
                                    <tr>
                                        <td><code>data.credit</code></td>
                                        <td>string</td>
                                        <td>Credit text to display</td>
                                    </tr>
                                    <tr>
                                        <td><code>data.token</code></td>
                                        <td>string</td>
                                        <td>MD5 hash for verification</td>
                                    </tr>
                                    <tr>
                                        <td><code>data.device</code></td>
                                        <td>integer</td>
                                        <td>Maximum devices allowed</td>
                                    </tr>
                                    <tr>
                                        <td><code>data.EXP</code></td>
                                        <td>datetime</td>
                                        <td>License expiration date</td>
                                    </tr>
                                    <tr>
                                        <td><code>data.rng</code></td>
                                        <td>integer</td>
                                        <td>Unix timestamp for token validation</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h5 class="mt-4">Error Responses</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Invalid Key</h6>
                                <pre class="language-json"><code>{
  "status": false,
  "reason": "USER OR GAME NOT REGISTERED"
}</code></pre>
                            </div>
                            <div class="col-md-6">
                                <h6>Expired Key</h6>
                                <pre class="language-json"><code>{
  "status": false,
  "reason": "EXPIRED KEY"
}</code></pre>
                            </div>
                            <div class="col-md-6">
                                <h6>Blocked User</h6>
                                <pre class="language-json"><code>{
  "status": false,
  "reason": "USER BLOCKED"
}</code></pre>
                            </div>
                            <div class="col-md-6">
                                <h6>Max Devices Reached</h6>
                                <pre class="language-json"><code>{
  "status": false,
  "reason": "MAX DEVICE REACHED"
}</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">🔐 Token Verification</h4>
                    </div>
                    <div class="card-body">
                        <p>To verify the token is authentic:</p>
                        <ol>
                            <li>Concatenate: <code>game + "-" + user_key + "-" + serial + "-" + staticWords</code></li>
                            <li>Calculate MD5 hash of the concatenated string</li>
                            <li>Compare with the <code>token</code> from response</li>
                            <li>Verify <code>rng + 30 > current_time</code> (token valid for 30 seconds)</li>
                        </ol>

                        <div class="alert alert-light-warning">
                            <strong>Static Words:</strong> <code>Vm8Lk7Uj2JmsjCPVPVjrLa7zgfx3uz9E</code>
                        </div>

                        <h5 class="mt-4">Example Verification (C++)</h5>
                        <pre class="language-cpp"><code>std::string auth = packageName;        // "com.tencent.ig"
auth += "-";
auth += userKey;                   // "ABC123XYZ456"
auth += "-";
auth += serial;                    // "550e8400-e29b-41d4-a716-446655440000"
auth += "-";
auth += "Vm8Lk7Uj2JmsjCPVPVjrLa7zgfx3uz9E";

std::string calculatedToken = CalcMD5(auth);
bool isValid = (calculatedToken == responseToken) && (rng + 30 > time(0));</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">📱 Complete Integration Code</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-light-success">
                            <i class="bi bi-check-circle"></i> <strong>Ready to Use:</strong> Copy the code below and paste into your Main.cpp
                        </div>

                        <pre class="language-cpp"><code>extern "C" {
JNIEXPORT jstring JNICALL
Java_com_android_support_TechnicalAkash1_Check(JNIEnv *env, jclass clazz, jobject mContext, jstring mUserKey) {
    auto userKey = env->GetStringUTFChars(mUserKey, 0);

    std::string hwid = userKey;
    hwid += GetAndroidID(env, mContext);
    hwid += GetDeviceModel(env);
    hwid += GetDeviceBrand(env);

    std::string UUID = GetDeviceUniqueIdentifier(env, hwid.c_str());

    std::string errMsg;

    struct MemoryStruct chunk{};
    chunk.memory = (char *) malloc(1);
    chunk.size = 0;

    CURL *curl;
    CURLcode res;
    curl = curl_easy_init();
    if (curl) {
        curl_easy_setopt(curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_easy_setopt(curl, CURLOPT_URL, "https://hclou.com/connect");
        curl_easy_setopt(curl, CURLOPT_FOLLOWLOCATION, 1L);
        curl_easy_setopt(curl, CURLOPT_DEFAULT_PROTOCOL, "https");
        struct curl_slist *headers = NULL;
        headers = curl_slist_append(headers, "Content-Type: application/x-www-form-urlencoded");
        curl_easy_setopt(curl, CURLOPT_HTTPHEADER, headers);

        // Get package name dynamically for multi-game support
        const char* packageName = GetPackageName(env, mContext);

        char data[4096];
        sprintf(data, "game=%s&user_key=%s&serial=%s", packageName, userKey, UUID.c_str());
        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, data);

        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, WriteMemoryCallback);
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, (void *) &chunk);

        curl_easy_setopt(curl, CURLOPT_SSL_VERIFYPEER, 0L);
        curl_easy_setopt(curl, CURLOPT_SSL_VERIFYHOST, 0L);

        res = curl_easy_perform(curl);
        if (res == CURLE_OK) {
            try {
                json result = json::parse(chunk.memory);
                if (result["status"] == true) {
                    std::string token = result["data"]["token"].get<std::string>();
                    time_t rng = result["data"]["rng"].get<time_t>();

                    if (rng + 30 > time(0)) {
                        std::string auth = packageName;
                        auth += "-";
                        auth += userKey;
                        auth += "-";
                        auth += UUID;
                        auth += "-";
                        auth += "Vm8Lk7Uj2JmsjCPVPVjrLa7zgfx3uz9E";
                        std::string outputAuth = CalcMD5(auth);

                        g_Token = token;
                        g_Auth = outputAuth;

                        bValid = g_Token == g_Auth;

                        if (bValid) {
                           // License verified successfully
                        }
                    }
                } else {
                    errMsg = result["reason"].get<std::string>();
                }
            } catch (json::exception &e) {
                errMsg = "{";
                errMsg += e.what();
                errMsg += "}\n{";
                errMsg += chunk.memory;
                errMsg += "}";
            }
        } else {
            errMsg = curl_easy_strerror(res);
        }
    }
    curl_easy_cleanup(curl);
    return bValid ? env->NewStringUTF("OK") : env->NewStringUTF(errMsg.c_str());
}
}</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">🎮 Supported Games</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Game</th>
                                        <th>Package ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>PUBG Mobile Global</td>
                                        <td><code>com.tencent.ig</code></td>
                                    </tr>
                                    <tr>
                                        <td>PUBG Mobile KR</td>
                                        <td><code>com.pubg.krmobile</code></td>
                                    </tr>
                                    <tr>
                                        <td>PUBG Mobile VN</td>
                                        <td><code>com.vng.pubgmobile</code></td>
                                    </tr>
                                    <tr>
                                        <td>Free Fire</td>
                                        <td><code>com.dts.freefireth</code></td>
                                    </tr>
                                    <tr>
                                        <td>Mobile Legends</td>
                                        <td><code>com.mobile.legends</code></td>
                                    </tr>
                                    <tr>
                                        <td>Call of Duty Mobile</td>
                                        <td><code>com.activision.callofduty.shooter</code></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-light-info">
                            <i class="bi bi-lightbulb"></i> <strong>Tip:</strong> Use <code>GetPackageName(env, mContext)</code> to automatically detect the game package ID.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">⚡ Rate Limiting</h4>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>No rate limit currently enforced</li>
                            <li><strong>Recommended:</strong> Cache validation result for 5-10 minutes</li>
                            <li>Re-validate on app restart or after expiration</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">📞 Support</h4>
                    </div>
                    <div class="card-body">
                        <p>For issues or questions:</p>
                        <ul>
                            <li><strong>Telegram:</strong> <a href="https://t.me/QMODZzZ" target="_blank">@QMODZzZ</a></li>
                            <li><strong>Website:</strong> <a href="https://hclou.com">hclou.com</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">📝 Changelog</h4>
                    </div>
                    <div class="card-body">
                        <h5>v1.0 (May 7, 2026)</h5>
                        <ul>
                            <li>Initial API release</li>
                            <li>Package-based authentication system</li>
                            <li>Multi-device support</li>
                            <li>Token verification with MD5</li>
                            <li>Auto-detect game package ID</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
