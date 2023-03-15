# Yes to DKIM
Status: Accepted
## Summary
While drilling down into DKIM,
realizing that the signature spans also some personal headers,
we decided to stick to DKIM verification,
winning fraud prevention,
accepting handling personal data, and requiring full headers.
## Context
```
DKIM-Signature: v=1; a=rsa-sha256; c=relaxed/relaxed; d=mailchimpapp.net;
	s=k3; t=1656259356; x=1656561756;
	i=info=3Dvoeoe.de@mailchimpapp.net;
	bh=2holHFq9uNV/5MmJviD2rxFcxoDxyz5A9LepQL0V9WE=;
	h=Subject:From:Reply-To:To:Date:Message-ID:X-MC-User:Feedback-ID:
	 List-ID:List-Unsubscribe:List-Unsubscribe-Post:Content-Type:
	 MIME-Version:CC:Date:Subject:From;
	b=OXSXp60w6LYE4eyerSWNAwHGxwqnPtdZdIHE+uaJwhlc45V+UlRaSVAAthRY3Ud3y
	 MTi0BiKs70hoiRTLDUrOF69/bHMBNvP10pRb2c+wNRpHISnN5FIlUjNrWRgV4qKXKi
	 KstOy/r3Cm+1gG8HKu9YR3P6lmRJnBU73RtkvnweP36xYeMZRobQscsMi1YFVgWhH4
	 RcbeHqQL7WZns/R0DMcnKzPuyxSJJadz4ayMAL8C/8PLlRr+SdAVA8HIJUJMapaZpr
	 songp0AcMeTcVxwLcV2veCxZiw7GYLNXu0p6g+QEVxLrfNZp3pOsEc0d0lxniBEI1N
	 seWtSAFLXgwfw==
```
