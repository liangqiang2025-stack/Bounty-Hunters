import logging
import uuid
from contextvars import ContextVar

request_id_var: ContextVar[str | None] = ContextVar("request_id", default=None)

logger = logging.getLogger("fastapi")


def get_request_id() -> str | None:
    """Get the current request ID from context."""
    return request_id_var.get()


def set_request_id(request_id: str | None = None) -> str:
    """Set a request ID for the current context. Generates one if not provided."""
    if request_id is None:
        request_id = uuid.uuid4().hex[:12]
    request_id_var.set(request_id)
    return request_id


class RequestIDFilter(logging.Filter):
    """Logging filter that adds request_id to log records."""

    def filter(self, record: logging.LogRecord) -> bool:
        record.request_id = get_request_id() or "-"
        return True


logger.addFilter(RequestIDFilter())
