# Security Audit Report
**Date:** 2025-01-27  
**Project:** Webist Laravel Application  
**Severity Levels:** 🔴 Critical | 🟠 High | 🟡 Medium | 🟢 Low

---

## Executive Summary

This security audit identified **15 security vulnerabilities** across multiple categories:
- **Critical Issues:** 3
- **High Issues:** 5
- **Medium Issues:** 4
- **Low Issues:** 3

---

## 🔴 CRITICAL ISSUES

### 1. Mass Assignment Vulnerabilities
**Location:** Multiple Model Files  
**Severity:** 🔴 Critical  
**Risk:** Attackers can modify protected fields by manipulating request data

**Affected Files:**
- `app/Models/User.php` - Line 16: `protected $guarded = [];`
- `app/Models/Report.php` - Line 15: `protected $guarded = [];`
- `app/Models/Sanction.php` - Line 10: `protected $guarded = [];`
- `app/Models/Officer.php` - Line 33: `protected $guarded = ['role'];`
- `app/Models/Role.php` - Line 9: `protected $guarded = [];`
- `app/Models/Sortie.php` - Line 14: `protected $guarded = [];`

**Issue:** Using `protected $guarded = []` allows mass assignment of ANY field, including sensitive ones like `role_id`, `officer_id`, `password`, etc.

**Recommendation:**
```php
// Instead of $guarded = [], use $fillable
protected $fillable = [
    'username',
    'phone',
    'bat',
    // Explicitly list allowed fields
];
```

---

### 2. Missing Authorization Checks - Insecure Direct Object References
**Location:** `app/Http/Controllers/ReportController.php`  
**Severity:** 🔴 Critical  
**Risk:** Users can access/modify reports belonging to other officers

**Issues Found:**

#### 2.1 Report Show Method (Line 182)
```php
public function show($officer, $report_id)
{
    $report = Report::with(['student.section', 'sanction'])->findOrFail($report_id);
    // ❌ No check if $officer can access this report
    // ❌ Any authenticated user can view any report by ID
}
```

#### 2.2 Report Avis Method (Line 279)
```php
public function avis(Officer $id, Report $report, Request $request)
{
    $officer = $id;
    // TODO : check if the officer is the owner of the report  ❌ NOT IMPLEMENTED
    // ❌ Any officer can modify any report's avis
}
```

#### 2.3 Report Refuse Method (Line 369)
```php
public function refuse(Officer $id, Report $report, Request $request)
{
    // ❌ No authorization check - any officer can refuse any report
}
```

#### 2.4 Sanction Update/Destroy (Line 167, 193)
```php
public function update(Officer $id, Sanction $sanction, Request $request)
{
    // ❌ No check if $id officer owns or can modify this sanction
}

public function destroy(Officer $id, Sanction $sanction)
{
    // ❌ No authorization check
}
```

**Recommendation:** Add authorization checks:
```php
public function show($officer, $report_id)
{
    $report = Report::with(['student.section', 'sanction'])->findOrFail($report_id);
    
    // Check if officer can access this report
    if ($report->officer_id !== $officer->id && 
        $report->destination !== $officer->id &&
        !$this->canAccessReport($officer, $report)) {
        abort(403, 'Unauthorized access to this report');
    }
    
    // ... rest of code
}
```

---

### 3. CSRF Protection Disabled for Critical Routes
**Location:** `app/Http/Middleware/VerifyCsrfToken.php`  
**Severity:** 🔴 Critical  
**Risk:** CSRF attacks can be performed on critical operations

**Issue:**
```php
protected $except = [
    'login',           // ✅ OK - public route
    'logout',          // ❌ CRITICAL - should be protected
    '*/avis/*',        // ❌ CRITICAL - modifying reports
    '*/refuse/*',      // ❌ CRITICAL - refusing reports
    '*/reports',       // ❌ CRITICAL - creating reports
    '*/sanctions*',    // ❌ CRITICAL - creating/updating sanctions
    '*/weekends*',     // ❌ CRITICAL - modifying weekend data
];
```

**Recommendation:** Remove all except `login` from CSRF exceptions. Use proper CSRF tokens in frontend.

---

## 🟠 HIGH ISSUES

### 4. Session Security Configuration
**Location:** `config/session.php`  
**Severity:** 🟠 High  
**Risk:** Session hijacking, cookie theft

**Issues:**
- Line 50: `'encrypt' => env('SESSION_ENCRYPT', false)` - Sessions not encrypted by default
- Line 172: `'secure' => env('SESSION_SECURE_COOKIE', false)` - Cookies sent over HTTP (should be HTTPS only in production)

**Recommendation:**
```php
'encrypt' => env('SESSION_ENCRYPT', true),
'secure' => env('SESSION_SECURE_COOKIE', true), // Set to true in production
```

---

### 5. User Model Security Issues
**Location:** `app/Models/User.php`  
**Severity:** 🟠 High

**Issues:**
1. **Line 16:** `protected $guarded = [];` - Allows mass assignment
2. **Line 18-24:** `$fillable` is defined but `$guarded = []` overrides it
3. **Line 36-39:** Password hashing in mutator is good, but password can still be mass-assigned

**Recommendation:**
```php
protected $guarded = ['id', 'created_at', 'updated_at'];
// Remove $guarded = [] and rely on $fillable
```

---

### 6. Notification Route Authorization Bypass
**Location:** `routes/web.php` - Line 40  
**Severity:** 🟠 High  
**Risk:** Users can mark notifications as read for other users

**Issue:**
```php
Route::post('/notifications/{officerId}/mark-as-read/{notificationId}', function ($officerId, $notificationId) {
    $user = User::findOrFail($officerId);
    // ❌ No check if authenticated user matches $officerId
    // Any user can mark any other user's notifications as read
});
```

**Recommendation:**
```php
Route::post('/notifications/{officerId}/mark-as-read/{notificationId}', function ($officerId, $notificationId) {
    $authenticatedUser = auth()->user();
    if ($authenticatedUser->id != $officerId) {
        abort(403, 'Unauthorized');
    }
    // ... rest of code
});
```

---

### 7. SQL Injection Risk with whereRaw
**Location:** `app/Http/Controllers/StudentController.php` - Line 42, 111  
**Severity:** 🟠 High  
**Risk:** Potential SQL injection if `$officer->bat` is user-controlled

**Issue:**
```php
$query->whereRaw('1 = 0'); // This is safe, but pattern is risky
```

While this specific case is safe, using `whereRaw` with user input is dangerous. The `$officer->bat` should be validated.

**Recommendation:** Ensure `$officer->bat` is always validated and use parameterized queries.

---

### 8. Missing Input Validation in Search
**Location:** `app/Http/Controllers/ReportController.php` - Line 32, 401  
**Severity:** 🟠 High  
**Risk:** Potential for SQL injection or XSS

**Issue:**
```php
if ($request->filled('search')) {
    $search = $request->get('search'); // ❌ No validation
    $query->where('title', 'like', "%{$search}%"); // Direct interpolation
}
```

**Recommendation:**
```php
$request->validate([
    'search' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\-_]+$/']
]);
```

---

## 🟡 MEDIUM ISSUES

### 9. Dynamic Column Name Construction
**Location:** `app/Http/Controllers/ReportController.php` - Line 301-309  
**Severity:** 🟡 Medium  
**Risk:** Potential for column name injection

**Issue:**
```php
$targetColumnKey = 'avis' . str_replace(' ', '_', $normalizedRole);
// Column name is constructed from user role, then matched against schema
```

While there's a check against schema columns, this pattern is risky.

**Recommendation:** Use a whitelist of allowed column names.

---

### 10. Password Minimum Length
**Location:** `app/Http/Controllers/OfficerController.php` - Line 60, 99  
**Severity:** 🟡 Medium

**Issue:** Minimum password length is 8 characters, which is acceptable but could be stronger.

**Recommendation:** Consider increasing to 12 characters and requiring complexity.

---

### 11. Missing Rate Limiting
**Location:** Authentication and API routes  
**Severity:** 🟡 Medium  
**Risk:** Brute force attacks

**Issue:** No rate limiting on login attempts or API endpoints.

**Recommendation:** Add rate limiting:
```php
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1'); // 5 attempts per minute
```

---

### 12. Information Disclosure in Error Messages
**Location:** Multiple controllers  
**Severity:** 🟡 Medium

**Issue:** Error messages may reveal system information:
- Database structure (column names in validation errors)
- File paths in exceptions
- Internal logic details

**Recommendation:** Use generic error messages in production, log detailed errors.

---

## 🟢 LOW ISSUES

### 13. Session Lifetime
**Location:** `config/session.php` - Line 35  
**Severity:** 🟢 Low

**Issue:** Default session lifetime is 120 minutes (2 hours), which may be too long for sensitive operations.

**Recommendation:** Consider reducing to 30-60 minutes for sensitive applications.

---

### 14. Missing HTTP Security Headers
**Severity:** 🟢 Low

**Recommendation:** Add security headers:
- X-Content-Type-Options: nosniff
- X-Frame-Options: DENY
- X-XSS-Protection: 1; mode=block
- Strict-Transport-Security (HSTS)
- Content-Security-Policy

---

### 15. API Routes Not Protected
**Location:** `routes/api.php`  
**Severity:** 🟢 Low

**Issue:** API routes may not have authentication middleware.

**Recommendation:** Ensure API routes use Sanctum or similar authentication.

---

## Recommendations Summary

### Immediate Actions (Critical):
1. ✅ Fix mass assignment vulnerabilities - use `$fillable` instead of `$guarded = []`
2. ✅ Add authorization checks to all report/sanction operations
3. ✅ Remove CSRF exceptions for critical routes (except login)

### Short-term (High Priority):
4. ✅ Enable session encryption
5. ✅ Fix notification route authorization
6. ✅ Add input validation to all search/filter operations
7. ✅ Validate user input in whereRaw queries

### Medium-term:
8. ✅ Add rate limiting to authentication
9. ✅ Implement proper error handling (hide sensitive info)
10. ✅ Add security headers middleware
11. ✅ Review and strengthen password requirements

---

## Testing Recommendations

1. **Penetration Testing:** Test for IDOR vulnerabilities
2. **CSRF Testing:** Verify all state-changing operations require CSRF tokens
3. **Authorization Testing:** Test that users cannot access resources they don't own
4. **Input Validation Testing:** Test with malicious input (SQL injection, XSS payloads)
5. **Session Security Testing:** Test session fixation and hijacking scenarios

---

## Compliance Notes

- Ensure GDPR compliance for user data handling
- Implement audit logging for sensitive operations
- Consider adding 2FA for administrative accounts
- Regular security updates for dependencies

---

**Report Generated:** 2025-01-27  
**Next Review Recommended:** After implementing critical fixes

