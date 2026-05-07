# MBBank Auto Recharge Setup

## 1. Cấu hình API Key

Thêm API key vào file `.env`:

```
MBBANK_API_KEY=your_api_key_here
```

## 2. Import database

Chạy file SQL để tạo bảng transactions:

```bash
mysql -u username -p database_name < database_transactions.sql
```

## 3. Cấu hình Cron Job

Thêm cron job để tự động check giao dịch mỗi 3 phút:

```bash
crontab -e
```

Thêm dòng sau:

```
*/3 * * * * curl -s https://yourdomain.com/recharge/auto-check > /dev/null 2>&1
```

Hoặc dùng wget:

```
*/3 * * * * wget -q -O /dev/null https://yourdomain.com/recharge/auto-check
```

## 4. Hướng dẫn nạp tiền cho user

User chuyển khoản với nội dung: `NAP {user_id}`

Ví dụ:
- User ID 5 chuyển khoản với nội dung: `NAP 5`
- User ID 123 chuyển khoản với nội dung: `NAP 123`

Hệ thống sẽ tự động:
1. Gọi API MBBank mỗi 3 phút
2. Tìm giao dịch có nội dung chứa `NAP {user_id}`
3. Kiểm tra user tồn tại
4. Kiểm tra giao dịch chưa được xử lý
5. Cộng tiền vào tài khoản user
6. Lưu lịch sử giao dịch

## 5. Test thủ công

Truy cập URL sau để test:

```
https://yourdomain.com/recharge/auto-check
```

Kết quả trả về JSON:

```json
{
    "success": true,
    "processed": 2,
    "total_amount": 500000,
    "message": "Processed 2 transactions, total: 500.000₫"
}
```

## 6. Logs

Check logs tại `writable/logs/` để debug:

```bash
tail -f writable/logs/log-*.log | grep MBBank
```
